document.querySelectorAll('.telefone').forEach((input) => {
    input.addEventListener('input', () => {
        const digits = input.value.replace(/\D/g, '').slice(0, 11);
        let formatted = digits;
        if (digits.length > 2) formatted = `(${digits.slice(0, 2)}) ${digits.slice(2)}`;
        if (digits.length > 7) formatted = `(${digits.slice(0, 2)}) ${digits.slice(2, 7)}-${digits.slice(7)}`;
        input.value = formatted;
    });
});
