document.querySelectorAll('.telefone').forEach((input) => {
    input.addEventListener('input', () => {
        const digits = input.value.replace(/\D/g, '').slice(0, 11);
        let formatted = digits;
        if (digits.length > 2) formatted = `(${digits.slice(0, 2)}) ${digits.slice(2)}`;
        if (digits.length > 7) formatted = `(${digits.slice(0, 2)}) ${digits.slice(2, 7)}-${digits.slice(7)}`;
        input.value = formatted;
    });
});

(function () {
    const form = document.querySelector('.auth-card');
    if (!form) return; // essa página não tem o form de login, não faz nada

    const campoSenha = form.querySelector('#senha');
    const botaoAlternar = form.querySelector('.alternar-senha');
    const botaoEntrar = form.querySelector('.auth-cta');
    const textoBotao = form.querySelector('.auth-cta-texto');

    // 1) Mostrar/ocultar senha
    botaoAlternar.addEventListener('click', function () {
        const mostrando = campoSenha.type === 'text';
        campoSenha.type = mostrando ? 'password' : 'text';
        botaoAlternar.textContent = mostrando ? 'Mostrar' : 'Ocultar';
        botaoAlternar.setAttribute('aria-label', mostrando ? 'Mostrar senha' : 'Ocultar senha');
    });

    // 2) Validação em tempo real
    function validarCampo(input) {
        const wrapper = input.closest('.campo');
        const erro = wrapper.querySelector('.campo-erro');

        if (input.validity.valid) {
            wrapper.classList.remove('invalido');
            erro.textContent = '';
            return true;
        }

        wrapper.classList.add('invalido');
        if (input.validity.valueMissing) {
            erro.textContent = 'Este campo é obrigatório.';
        } else if (input.validity.typeMismatch) {
            erro.textContent = 'Digite um e-mail válido.';
        } else {
            erro.textContent = 'Valor inválido.';
        }
        return false;
    }

    form.querySelectorAll('.campo input').forEach(function (input) {
        input.addEventListener('blur', function () { validarCampo(input); });
        input.addEventListener('input', function () {
            if (input.closest('.campo').classList.contains('invalido')) {
                validarCampo(input);
            }
        });
    });

    // 3) Envio do formulário
    form.addEventListener('submit', function (evento) {
        const campos = Array.from(form.querySelectorAll('.campo input'));
        const valido = campos.map(validarCampo).every(Boolean);

        if (!valido) {
            evento.preventDefault();
            form.classList.remove('tremer');
            void form.offsetWidth; // força o navegador a "reiniciar" a animação
            form.classList.add('tremer');
            campos.find(function (c) { return !c.validity.valid; }).focus();
            return;
        }

        botaoEntrar.disabled = true;
        textoBotao.textContent = 'Entrando...';
    });
})();