/**
 * validacoes.js — NexusDev WEB / Distribuidora CFA
 * Validações em tempo real para formulários do sistema.
 * Inclui: CPF, CNPJ, Telefone, CEP, Nome, E-mail, Senha forte.
 */

// ─────────────────────────────────────────────
// UTILITÁRIOS
// ─────────────────────────────────────────────

/** Remove tudo que não for dígito */
function apenasDigitos(valor) {
  return valor.replace(/\D/g, '');
}

/** Marca campo como válido (Bootstrap) */
function marcarValido(campo) {
  campo.classList.remove('is-invalid');
  campo.classList.add('is-valid');
}

/** Marca campo como inválido (Bootstrap) */
function marcarInvalido(campo) {
  campo.classList.remove('is-valid');
  campo.classList.add('is-invalid');
}

/** Limpa estado de validação */
function limparEstado(campo) {
  campo.classList.remove('is-valid', 'is-invalid');
}

/** Exibe/oculta mensagem de erro vinculada ao campo */
function definirMensagem(campo, msg) {
  const feedback = campo.nextElementSibling;
  if (feedback && feedback.classList.contains('invalid-feedback')) {
    feedback.textContent = msg;
  }
}

// ─────────────────────────────────────────────
// VALIDAÇÕES INDIVIDUAIS
// ─────────────────────────────────────────────

/**
 * Valida Nome — mínimo 3 caracteres, apenas letras e espaços
 */
function validarNome(campo) {
  const valor = campo.value.trim();
  if (valor.length < 3) {
    marcarInvalido(campo);
    definirMensagem(campo, 'Nome deve ter ao menos 3 caracteres.');
    return false;
  }
  if (!/^[A-Za-zÀ-ÖØ-öø-ÿ\s]+$/.test(valor)) {
    marcarInvalido(campo);
    definirMensagem(campo, 'Nome deve conter apenas letras.');
    return false;
  }
  marcarValido(campo);
  return true;
}

/**
 * Valida E-mail
 */
function validarEmail(campo) {
  const valor = campo.value.trim();
  const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if (!regex.test(valor)) {
    marcarInvalido(campo);
    definirMensagem(campo, 'Informe um e-mail válido.');
    return false;
  }
  marcarValido(campo);
  return true;
}

/**
 * Valida Telefone — 10 ou 11 dígitos (fixo ou celular)
 * Técnica: remove não-dígitos e checa o tamanho (sem regex complexa)
 */
function validarTelefone(campo) {
  const digitos = apenasDigitos(campo.value);
  if (digitos.length < 10 || digitos.length > 11) {
    marcarInvalido(campo);
    definirMensagem(campo, 'Telefone deve ter 10 ou 11 dígitos numéricos.');
    return false;
  }
  marcarValido(campo);
  return true;
}

/**
 * Valida CEP — exatamente 8 dígitos
 */
function validarCEP(campo) {
  const digitos = apenasDigitos(campo.value);
  if (digitos.length !== 8) {
    marcarInvalido(campo);
    definirMensagem(campo, 'CEP deve ter 8 dígitos.');
    return false;
  }
  marcarValido(campo);
  return true;
}

/**
 * Valida CPF — 11 dígitos + dígitos verificadores
 */
function validarCPF(campo) {
  const cpf = apenasDigitos(campo.value);

  if (cpf.length !== 11 || /^(\d)\1{10}$/.test(cpf)) {
    marcarInvalido(campo);
    definirMensagem(campo, 'CPF inválido.');
    return false;
  }

  // Verifica dígitos verificadores
  let soma = 0;
  for (let i = 0; i < 9; i++) soma += parseInt(cpf[i]) * (10 - i);
  let resto = (soma * 10) % 11;
  if (resto === 10 || resto === 11) resto = 0;
  if (resto !== parseInt(cpf[9])) {
    marcarInvalido(campo);
    definirMensagem(campo, 'CPF inválido.');
    return false;
  }

  soma = 0;
  for (let i = 0; i < 10; i++) soma += parseInt(cpf[i]) * (11 - i);
  resto = (soma * 10) % 11;
  if (resto === 10 || resto === 11) resto = 0;
  if (resto !== parseInt(cpf[10])) {
    marcarInvalido(campo);
    definirMensagem(campo, 'CPF inválido.');
    return false;
  }

  marcarValido(campo);
  return true;
}

/**
 * Valida CNPJ — 14 dígitos + dígitos verificadores
 */
function validarCNPJ(campo) {
  const cnpj = apenasDigitos(campo.value);

  if (cnpj.length !== 14 || /^(\d)\1{13}$/.test(cnpj)) {
    marcarInvalido(campo);
    definirMensagem(campo, 'CNPJ inválido.');
    return false;
  }

  const calcDigito = (cnpj, tamanho) => {
    let soma = 0;
    let pos = tamanho - 7;
    for (let i = tamanho; i >= 1; i--) {
      soma += parseInt(cnpj.charAt(tamanho - i)) * pos--;
      if (pos < 2) pos = 9;
    }
    const resultado = soma % 11 < 2 ? 0 : 11 - (soma % 11);
    return resultado;
  };

  if (calcDigito(cnpj, 12) !== parseInt(cnpj[12]) ||
      calcDigito(cnpj, 13) !== parseInt(cnpj[13])) {
    marcarInvalido(campo);
    definirMensagem(campo, 'CNPJ inválido.');
    return false;
  }

  marcarValido(campo);
  return true;
}

/**
 * Valida Senha forte:
 *   1. Mínimo 8 caracteres
 *   2. Pelo menos 1 número
 *   3. Pelo menos 1 caractere especial (!@#$%^&*...)
 * Técnica: verifica caractere especial percorrendo string de especiais permitidos
 */
function validarSenha(campo) {
  const senha = campo.value;
  const especiais = '!@#$%^&*()-_=+[]{};:\'",.<>?/\\|`~';
  const msgs = [];

  if (senha.length < 8)           msgs.push('mínimo 8 caracteres');
  if (!/\d/.test(senha))          msgs.push('pelo menos 1 número');

  // Verifica se há ao menos um caractere especial (percorre a string de especiais)
  let temEspecial = false;
  for (let i = 0; i < senha.length; i++) {
    if (especiais.split('').some(e => e === senha[i])) {
      temEspecial = true;
      break;
    }
  }
  if (!temEspecial) msgs.push('pelo menos 1 caractere especial (!@#$...)');

  if (msgs.length > 0) {
    marcarInvalido(campo);
    definirMensagem(campo, 'Senha fraca: ' + msgs.join(', ') + '.');
    return false;
  }

  marcarValido(campo);
  return true;
}

/**
 * Valida Select — bloqueia placeholder (valor vazio)
 */
function validarSelect(campo) {
  if (!campo.value || campo.value === '') {
    marcarInvalido(campo);
    definirMensagem(campo, 'Selecione uma opção.');
    return false;
  }
  marcarValido(campo);
  return true;
}

// ─────────────────────────────────────────────
// INICIALIZAÇÃO AUTOMÁTICA
// Detecta campos pelos atributos name/type e
// aplica as validações correspondentes ao evento "input" ou "change"
// ─────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', function () {

  /**
   * Mapeia seletores de campo para suas funções de validação.
   * Adiciona também a mensagem .invalid-feedback dinamicamente
   * se ainda não existir no HTML.
   */
  const mapa = [
    // Nome de funcionário, drogaria, laboratório
    { seletor: 'input[name="funcionario[nome]"]',    fn: validarNome,      evento: 'input' },
    { seletor: 'input[name="drogaria[nome]"]',       fn: validarNome,      evento: 'input' },
    { seletor: 'input[name="laboratorio[nome]"]',    fn: validarNome,      evento: 'input' },

    // CPF
    { seletor: 'input[name="funcionario[cpf]"]',     fn: validarCPF,       evento: 'input' },

    // CNPJ
    { seletor: 'input[name="drogaria[cnpj]"]',       fn: validarCNPJ,      evento: 'input' },
    { seletor: 'input[name="laboratorio[cnpj]"]',    fn: validarCNPJ,      evento: 'input' },

    // Telefone
    { seletor: 'input[name="funcionario[telefone]"]', fn: validarTelefone, evento: 'input' },
    { seletor: 'input[name="drogaria[telefone]"]',    fn: validarTelefone, evento: 'input' },
    { seletor: 'input[name="laboratorio[telefone]"]', fn: validarTelefone, evento: 'input' },

    // CEP
    { seletor: 'input[name="funcionario[cep]"]',     fn: validarCEP,       evento: 'input' },
    { seletor: 'input[name="drogaria[cep]"]',        fn: validarCEP,       evento: 'input' },
    { seletor: 'input[name="laboratorio[cep]"]',     fn: validarCEP,       evento: 'input' },

    // E-mail
    { seletor: 'input[name="funcionario[email]"]',   fn: validarEmail,     evento: 'input' },
    { seletor: 'input[name="drogaria[email]"]',      fn: validarEmail,     evento: 'input' },
    { seletor: 'input[name="laboratorio[email]"]',   fn: validarEmail,     evento: 'input' },

    // Senha (funcionário)
    { seletor: 'input[name="funcionario[senha]"]',   fn: validarSenha,     evento: 'input' },

    // Selects (Função, etc.)
    { seletor: 'select[name="funcionario[funcao]"]', fn: validarSelect,    evento: 'change' },
  ];

  mapa.forEach(({ seletor, fn, evento }) => {
    const campo = document.querySelector(seletor);
    if (!campo) return;

    // Garante que existe uma div .invalid-feedback logo após o campo
    if (!campo.nextElementSibling || !campo.nextElementSibling.classList.contains('invalid-feedback')) {
      const div = document.createElement('div');
      div.className = 'invalid-feedback';
      campo.insertAdjacentElement('afterend', div);
    }

    // Valida em tempo real ao digitar/alterar
    campo.addEventListener(evento, () => fn(campo));

    // Também valida ao sair do campo (blur)
    campo.addEventListener('blur', () => fn(campo));
  });

  // ─────────────────────────────────────────────
  // BLOQUEIO DE ENVIO DO FORMULÁRIO
  // Impede o POST se qualquer campo mapeado for inválido
  // ─────────────────────────────────────────────
  document.querySelectorAll('form').forEach(form => {
    form.addEventListener('submit', function (e) {
      let formularioValido = true;

      mapa.forEach(({ seletor, fn }) => {
        const campo = document.querySelector(seletor);
        if (!campo || !form.contains(campo)) return;
        // Campos opcionais (telefone, cep) só validam se preenchidos
        const opcionais = ['validarTelefone', 'validarCEP'];
        if (opcionais.includes(fn.name) && campo.value.trim() === '') return;
        if (!fn(campo)) formularioValido = false;
      });

      if (!formularioValido) {
        e.preventDefault();
        e.stopPropagation();
        // Rola até o primeiro campo inválido
        const primeiro = form.querySelector('.is-invalid');
        if (primeiro) primeiro.scrollIntoView({ behavior: 'smooth', block: 'center' });
      }
    });
  });

  // ─────────────────────────────────────────────
  // LOGIN — validação leve (não-vazio)
  // ─────────────────────────────────────────────
  const campoLogin = document.getElementById('login');
  const campoSenhaLogin = document.getElementById('senha');

  if (campoLogin) {
    campoLogin.addEventListener('blur', () => {
      if (campoLogin.value.trim() === '') {
        marcarInvalido(campoLogin);
      } else {
        marcarValido(campoLogin);
      }
    });
  }

  if (campoSenhaLogin) {
    // Na tela de login, a senha só precisa ser não-vazia (o back-end valida o hash)
    campoSenhaLogin.addEventListener('blur', () => {
      if (campoSenhaLogin.value.trim() === '') {
        marcarInvalido(campoSenhaLogin);
      } else {
        marcarValido(campoSenhaLogin);
      }
    });
  }

});
