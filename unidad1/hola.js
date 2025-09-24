function sumarNumeros() {
    const readline = require('readline').createInterface({
        input: process.stdin,
        output: process.stdout
    });

    readline.question('Ingresa el primer número: ', (numero1) => {
        readline.question('Ingresa el segundo número: ', (numero2) => {
            const suma = parseInt(numero1) + parseInt(numero2);
            console.log('La suma es:', suma);
            readline.close();
        });
    });
}

sumarNumeros();
