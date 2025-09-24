const express = require('express');
const mongoose = require('mongoose');

mongoose.connect('mongodb://localhost/restaurante', { useNewUrlParser: true, useUnifiedTopology: true });

const ItemSchema = new mongoose.Schema({
    nombre: String,
    cantidad: Number
});

const ComandaSchema = new mongoose.Schema({
    mesa: String,
    mesero: String,
    items: [ItemSchema],
    estado: { type: String, default: 'abierta' },
    fecha_creacion: { type: Date, default: Date.now }
});

const Comanda = mongoose.model('Comanda', ComandaSchema);

const app = express();
app.use(express.json());

// Crear comanda
app.post('/comandas', async (req, res) => {
    const comanda = new Comanda(req.body);
    await comanda.save();
    res.status(201).send(comanda);
});

// Listar comandas (opcional: filtrar por estado)
app.get('/comandas', async (req, res) => {
    const filtro = req.query.estado ? { estado: req.query.estado } : {};
    const comandas = await Comanda.find(filtro);
    res.send(comandas);
});

// Obtener comanda por ID
app.get('/comandas/:id', async (req, res) => {
    const comanda = await Comanda.findById(req.params.id);
    if (!comanda) return res.status(404).send({ error: 'Comanda no encontrada' });
    res.send(comanda);
});

// Modificar comanda
app.put('/comandas/:id', async (req, res) => {
    const comanda = await Comanda.findByIdAndUpdate(req.params.id, req.body, { new: true });
    if (!comanda) return res.status(404).send({ error: 'Comanda no encontrada' });
    res.send(comanda);
});

// Cerrar comanda
app.patch('/comandas/:id/cerrar', async (req, res) => {
    const comanda = await Comanda.findByIdAndUpdate(req.params.id, { estado: 'cerrada' }, { new: true });
    if (!comanda) return res.status(404).send({ error: 'Comanda no encontrada' });
    res.send(comanda);
});

app.listen(3000, () => console.log('API de comandas escuchando en puerto 3000'));