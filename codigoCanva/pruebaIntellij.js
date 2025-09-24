const prompt = require('prompt-sync')();

let inputA = prompt('Ingresa el primer número (o presiona Enter para usar 0):');
let a = parseFloat(inputA === '' ? '0' : inputA);

let inputB = prompt('Ingresa el segundo número (o presiona Enter para usar 0):');
let b = parseFloat(inputB === '' ? '0' : inputB);

if (isNaN(a) || isNaN(b)) {
    console.log('Por favor, ingresa números válidos.');
} else {
    console.log(`La suma de ${a} + ${b} = ${a + b}`);
}