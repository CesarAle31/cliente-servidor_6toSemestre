const express = require('express');
const router = express.Router();
const { getUsers, createUser, deleteUser, updateUser} = require('../controllers/userController');

router.get('/', getUsers);
router.post('/', createUser);
router.delete('/:id', deleteUser);
router.put('/:id', updateUser);

module.exports = router;



