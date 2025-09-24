const express = require('express');
const mongoose = require('mongoose');
const cors = require('cors'); //  agregar esto
require('dotenv').config();

const app = express();
app.use(cors()); //  muy importante para permitir fetch desde HTML
app.use(express.json());

const userRoutes = require('./routes/userRoutes');
app.use('/api/users', userRoutes);

mongoose.connect(process.env.MONGO_URI, {
    useNewUrlParser: true,
    useUnifiedTopology: true
})
    .then(() => console.log('conectado a mongodb'))
    .catch((err) => console.error('error al conectarse a mongodb', err));

const PORT = process.env.PORT || 3000;
app.listen(PORT, () => {
    console.log(`Servidor escuchando en el puerto ${PORT}`);
});
