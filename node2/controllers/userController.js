const User = require('../models/User');

// GET /api/users
const getUsers = async (req, res) => {
    try {
        const usuarios = await User.find();
        res.json(usuarios);
    } catch (error) {
        console.error('Error al obtener usuarios:', error);
        res.status(500).json({ error: 'Error interno del servidor' });
    }
};
// POST /api/users
const createUser = async (req, res) => {
    try {
        const { nombre, correo } = req.body;

        if (!nombre || !correo) {
            return res.status(400).json({ error: 'Faltan campos' });
        }

        const nuevoUsuario = new User({ nombre, correo });
        const guardado = await nuevoUsuario.save();
        res.json(guardado);
    } catch (error) {
        console.error('Error al crear usuario:', error);
        res.status(500).json({ error: 'Error interno del servidor' });
    }
};

const updateUser = async (req, res) => {
  try{
      const { id } = req.params;
      const { nombre, correo } = req.body;

      const user = await User.findByIdAndUpdate(id, {nombre, correo},
          {new: true});
      if (!user)
        return res.status(404).json({ message: 'usuario no encontrado' });
        res.json({ user});

  }  catch (error) {
      res.status(500).json({ message: 'Error al actualizar usuario:'});
  }
};



const deleteUser = async (req, res) => {
    try {
    const { id } = req.params;
    const user = await User.findByIdAndDelete(id);
    if (!user) return res.status(404).json({ message: 'usuario no encontrado' });
    res.json({message: 'usuario eliminado correctamente'});
    } catch (error) {
        res.status(500).json({ message: 'Error al eliminar'});
    }
}
module.exports = {
    getUsers,
    createUser,
    updateUser,
    deleteUser
};



