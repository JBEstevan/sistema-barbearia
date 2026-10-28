document.querySelectorAll('.telefone').forEach((input) => {
    input.addEventListener('input', () => {
        const digits = input.value.replace(/\D/g, '').slice(0, 11);
        let formatted = digits;
        if (digits.length > 2) formatted = `(${digits.slice(0, 2)}) ${digits.slice(2)}`;
        if (digits.length > 7) formatted = `(${digits.slice(0, 2)}) ${digits.slice(2, 7)}-${digits.slice(7)}`;
        input.value = formatted;
    });
});

// Formulários de autenticação (login e alterar senha): mesma lógica para todos
(function () {
    document.querySelectorAll('form.auth-card').forEach(iniciarFormulario);

    function iniciarFormulario(form) {
        const botaoEnviar = form.querySelector('.auth-cta');
        const textoBotao = form.querySelector('.auth-cta-texto');
        const campos = Array.from(form.querySelectorAll('.campo input'));

        // 1) Mostrar/ocultar senha: cada botão controla o input do seu próprio campo
        form.querySelectorAll('.alternar-senha').forEach(function (botao) {
            const input = botao.closest('.campo-senha').querySelector('input');
            botao.addEventListener('click', function () {
                const mostrando = input.type === 'text';
                input.type = mostrando ? 'password' : 'text';
                botao.textContent = mostrando ? 'Mostrar' : 'Ocultar';
                botao.setAttribute('aria-label', mostrando ? 'Mostrar senha' : 'Ocultar senha');
            });
        });

        // 2) Validação em tempo real
        function validarCampo(input) {
            const wrapper = input.closest('.campo');
            const erro = wrapper.querySelector('.campo-erro');

            // regra extra: campo que precisa ser igual a outro (data-igual-a="id-do-outro")
            if (input.dataset.igualA) {
                const outro = form.querySelector('#' + input.dataset.igualA);
                input.setCustomValidity(input.value !== outro.value ? 'diferente' : '');
            }

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
            } else if (input.validity.tooShort) {
                erro.textContent = 'Use ao menos ' + input.minLength + ' caracteres.';
            } else if (input.validity.customError) {
                erro.textContent = 'As senhas não coincidem.';
            } else {
                erro.textContent = 'Valor inválido.';
            }
            return false;
        }

        campos.forEach(function (input) {
            input.addEventListener('blur', function () { validarCampo(input); });
            input.addEventListener('input', function () {
                if (input.closest('.campo').classList.contains('invalido')) {
                    validarCampo(input);
                }
                // se outro campo depende deste (confirmação), revalida ele também
                campos.forEach(function (outro) {
                    if (outro.dataset.igualA === input.id && outro.value !== '') {
                        validarCampo(outro);
                    }
                });
            });
        });

        // 3) Medidor de força da senha (só aparece se o formulário tiver [data-forca])
        const forca = form.querySelector('[data-forca]');
        if (forca) {
            const campoNova = forca.closest('.campo').querySelector('input');
            const textoForca = forca.querySelector('.forca-texto');
            const nomes = ['', 'Muito curta', 'Razoável', 'Boa', 'Forte'];

            campoNova.addEventListener('input', function () {
                const s = campoNova.value;
                if (s === '') {
                    forca.hidden = true;
                    return;
                }
                const tipos = [/[a-z]/, /[A-Z]/, /\d/, /[^A-Za-z0-9]/].filter(function (r) { return r.test(s); }).length;
                let nivel = 1;
                if (s.length >= 8) {
                    nivel = 2;
                    if (s.length >= 12) nivel++;
                    if (tipos >= 3) nivel++;
                }
                forca.hidden = false;
                forca.dataset.nivel = nivel;
                textoForca.textContent = nomes[nivel];
            });
        }

        // 4) Envio
        form.addEventListener('submit', function (evento) {
            const valido = campos.map(validarCampo).every(Boolean);

            if (!valido) {
                evento.preventDefault();
                form.classList.remove('tremer');
                void form.offsetWidth; // reinicia a animação
                form.classList.add('tremer');
                campos.find(function (c) { return !c.validity.valid; }).focus();
                return;
            }

            botaoEnviar.disabled = true;
            textoBotao.textContent = botaoEnviar.dataset.enviando || 'Enviando...';
        });
    }
})();