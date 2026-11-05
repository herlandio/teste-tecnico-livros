import './bootstrap';

import * as bootstrap from 'bootstrap';

window.bootstrap = bootstrap;

const anoAtual = new Date().getFullYear();

const somenteDigitos = (valor, limite = null) => {
    const digitos = valor.replace(/\D+/g, '');

    return limite ? digitos.slice(0, limite) : digitos;
};

const formatarMoeda = (valor) => {
    const digitos = somenteDigitos(valor);

    if (digitos === '') {
        return '';
    }

    return (Number(digitos) / 100).toLocaleString('pt-BR', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    });
};

const formatarMoedaInicial = (valor) => {
    if (valor === null || valor === undefined || valor === '') {
        return '';
    }

    const numero = Number(String(valor).replace(',', '.'));

    if (Number.isNaN(numero)) {
        return '';
    }

    return numero.toLocaleString('pt-BR', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    });
};

const moedaParaDecimal = (valor) => {
    const digitos = somenteDigitos(valor);

    return digitos === '' ? '' : (Number(digitos) / 100).toFixed(2);
};

const feedbackDoCampo = (campo) => campo.parentElement.querySelector('[data-feedback]');

const atualizarFeedback = (campo) => {
    const feedback = feedbackDoCampo(campo);

    if (!feedback) {
        return;
    }

    campo.classList.toggle('is-invalid', !campo.validity.valid);
    feedback.textContent = campo.validity.valid ? '' : campo.validationMessage;
};

const permitirSomenteDigitos = (campo) => {
    campo.addEventListener('keydown', (event) => {
        if (event.ctrlKey || event.metaKey || event.altKey) {
            return;
        }

        if (event.key.length === 1 && !/[0-9]/.test(event.key)) {
            event.preventDefault();
        }
    });
};

const validarAno = (campo) => {
    const ano = Number(campo.value);

    let mensagem = '';

    if (campo.value !== '' && campo.value.length !== 4) {
        mensagem = 'Informe o ano com 4 dígitos.';
    } else if (campo.value !== '' && (ano < 1000 || ano > anoAtual)) {
        mensagem = `Informe um ano entre 1000 e ${anoAtual}.`;
    }

    campo.setCustomValidity(mensagem);
    atualizarFeedback(campo);

    return mensagem === '';
};

const validarEdicao = (campo) => {
    const invalido = campo.value !== '' && Number(campo.value) < 1;

    campo.setCustomValidity(invalido ? 'Informe uma edição maior ou igual a 1.' : '');
    atualizarFeedback(campo);

    return !invalido;
};

const validarValor = (campo) => {
    const invalido = campo.required && campo.value === '';

    campo.setCustomValidity(invalido ? 'Informe o valor do livro.' : '');
    atualizarFeedback(campo);

    return !invalido;
};

const configurarAno = (campo) => {
    campo.value = somenteDigitos(campo.value, 4);

    const hint = campo.parentElement.querySelector('[data-ano-hint]');

    if (hint) {
        hint.textContent = `Entre 1000 e ${anoAtual}.`;
    }

    permitirSomenteDigitos(campo);

    campo.addEventListener('input', () => {
        campo.value = somenteDigitos(campo.value, 4);
        campo.setCustomValidity('');
        atualizarFeedback(campo);
    });

    campo.addEventListener('blur', () => validarAno(campo));
};

const configurarEdicao = (campo) => {
    campo.value = somenteDigitos(campo.value).replace(/^0+(?=\d)/, '');

    permitirSomenteDigitos(campo);

    campo.addEventListener('input', () => {
        campo.value = somenteDigitos(campo.value).replace(/^0+(?=\d)/, '');
        campo.setCustomValidity('');
        atualizarFeedback(campo);
    });

    campo.addEventListener('blur', () => validarEdicao(campo));
};

const configurarValor = (campo) => {
    campo.value = formatarMoedaInicial(campo.value);

    campo.addEventListener('input', () => {
        campo.value = formatarMoeda(campo.value);
        campo.setCustomValidity('');
        atualizarFeedback(campo);
    });

    campo.addEventListener('blur', () => validarValor(campo));
};

const configurarFormulario = (form) => {
    form.noValidate = true;

    form.querySelectorAll('[data-mask="ano"]').forEach(configurarAno);
    form.querySelectorAll('[data-numeric="inteiro"]').forEach(configurarEdicao);
    form.querySelectorAll('[data-mask="moeda"]').forEach(configurarValor);

    form.addEventListener('submit', (event) => {
        let valido = true;

        form.querySelectorAll('[data-mask="ano"]').forEach((campo) => {
            valido = validarAno(campo) && valido;
        });

        form.querySelectorAll('[data-numeric="inteiro"]').forEach((campo) => {
            valido = validarEdicao(campo) && valido;
        });

        form.querySelectorAll('[data-mask="moeda"]').forEach((campo) => {
            valido = validarValor(campo) && valido;
        });

        if (!form.checkValidity()) {
            valido = false;
        }

        if (!valido) {
            event.preventDefault();

            const primeiroInvalido = form.querySelector(':invalid');

            if (primeiroInvalido) {
                primeiroInvalido.focus();
                primeiroInvalido.reportValidity();
            }

            return;
        }

        form.querySelectorAll('[data-mask="moeda"]').forEach((campo) => {
            campo.value = moedaParaDecimal(campo.value);
        });
    });
};

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('form').forEach(configurarFormulario);
});
