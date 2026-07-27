const questions = [
  { id: 'birthDate', category: 'Sobre você', title: 'Qual é sua data de nascimento?', type: 'date' },
  { id: 'sex', category: 'Sobre você', title: 'Qual é seu sexo?', type: 'single', options: ['Feminino', 'Masculino', 'Prefiro não informar'] },
  { id: 'height', category: 'Sobre você', title: 'Qual é sua altura?', description: 'Informe em centímetros.', type: 'number', unit: 'cm', min: 50, max: 250, step: 1 },
  { id: 'currentWeight', category: 'Seu momento', title: 'Qual é seu peso atual?', description: 'Informe em quilogramas.', type: 'number', unit: 'kg', min: 20, max: 500, step: 0.1 },
  { id: 'goal', category: 'Seu objetivo', title: 'Qual é seu principal objetivo?', type: 'single', options: ['Emagrecer', 'Ganhar massa muscular', 'Melhorar minha alimentação', 'Criar hábitos saudáveis', 'Controlar um tratamento de saúde', 'Outro'] },
  { id: 'hasGoalWeight', category: 'Seu objetivo', title: 'Você tem uma meta de peso?', type: 'yesno' },
  { id: 'goalWeight', category: 'Seu objetivo', title: 'Qual é o peso que você deseja alcançar?', description: 'Informe em quilogramas.', type: 'number', unit: 'kg', min: 20, max: 500, step: 0.1, condition: answers => answers.hasGoalWeight === 'Sim' },
  { id: 'goalDeadline', category: 'Seu objetivo', title: 'Para quando você deseja alcançar essa meta?', description: 'Informe o prazo que faz sentido para o seu momento.', type: 'duration', condition: answers => answers.hasGoalWeight === 'Sim' },
  { id: 'activityFrequency', category: 'Sua rotina', title: 'Você pratica atividade física?', type: 'single', options: ['Nunca', 'Menos de 1 vez por semana', '1 a 2 vezes por semana', '3 a 5 vezes por semana', 'Quase todos os dias'] },
  { id: 'dietQuality', category: 'Sua rotina', title: 'Como você avalia sua alimentação hoje?', type: 'single', options: ['Muito boa', 'Boa', 'Regular', 'Precisa melhorar bastante'] },
  { id: 'continuousMedication', category: 'Sua saúde', title: 'Você utiliza algum medicamento continuamente?', type: 'yesno' },
  { id: 'medications', category: 'Sua saúde', title: 'Quais medicamentos você utiliza?', type: 'text', placeholder: 'Digite os medicamentos que utiliza', condition: answers => answers.continuousMedication === 'Sim' },
  { id: 'weightLossMedication', category: 'Sua saúde', title: 'Você utiliza algum medicamento para emagrecimento?', type: 'single', options: ['Tirzepatida (Mounjaro, Zepbound)', 'Semaglutida (Ozempic, Wegovy)', 'Liraglutida (Saxenda)', 'Outro', 'Não utilizo'] },
  { id: 'conditions', category: 'Sua saúde', title: 'Você possui alguma dessas condições?', description: 'Você pode selecionar mais de uma opção.', type: 'multi', options: ['Diabetes', 'Hipertensão', 'Colesterol alto', 'Resistência à insulina', 'SOP (Síndrome dos Ovários Policísticos)', 'Outra', 'Nenhuma das anteriores'], exclusive: 'Nenhuma das anteriores' },
  { id: 'foodRestrictions', category: 'Sua saúde', title: 'Você possui alguma restrição alimentar?', description: 'Você pode selecionar mais de uma opção.', type: 'multi', options: ['Intolerância à lactose', 'Intolerância ao glúten', 'Vegetariano', 'Vegano', 'Outra', 'Nenhuma'], exclusive: 'Nenhuma' },
  { id: 'mirrorFeeling', category: 'Como você se sente', title: 'Quando você se olha no espelho hoje, como se sente?', type: 'single', options: ['Muito satisfeito(a)', 'Satisfeito(a)', 'Indiferente', 'Insatisfeito(a)', 'Muito insatisfeito(a)'] },
  { id: 'appearanceSelfEsteem', category: 'Como você se sente', title: 'O quanto sua aparência influencia sua autoestima?', type: 'single', options: ['Nada', 'Um pouco', 'Bastante', 'Muito'] },
  { id: 'biggestDiscomfort', category: 'Como você se sente', title: 'O que mais incomoda você hoje?', type: 'single', options: ['Meu peso', 'Minha alimentação', 'Minha aparência', 'Minha saúde', 'Minha falta de disciplina', 'Minha disposição', 'Outro'] },
  { id: 'routineDropout', category: 'Sua jornada', title: 'Com que frequência você começa uma rotina saudável e acaba desistindo?', type: 'single', options: ['Nunca', 'Às vezes', 'Frequentemente', 'Quase sempre'] },
  { id: 'journeyReason', category: 'Sua jornada', title: 'O que fez você decidir começar essa jornada agora?', type: 'single', options: ['❤️ Quero voltar a gostar de mim.', '👨‍👩‍👧 Quero ter mais saúde para minha família.', '💪 Quero me sentir melhor no meu corpo.', '🩺 Meu médico recomendou que eu cuidasse da minha saúde.', '👕 Quero voltar a vestir as roupas que gosto.', '🏃 Quero ter mais disposição no dia a dia.', '📉 Estou preocupado(a) com meu peso.', '🩸 Quero controlar melhor minha saúde.', '💉 Comecei um tratamento para emagrecimento.', '🌱 Quero criar hábitos mais saudáveis.', '✨ Quero melhorar minha autoestima.', '📝 Outro motivo...'] },
  { id: 'otherReason', category: 'Sua jornada', title: 'Quer compartilhar qual é esse motivo?', description: 'Esta resposta é opcional.', type: 'text', placeholder: 'Escreva aqui, se quiser', optional: true, condition: answers => answers.journeyReason === '📝 Outro motivo...' },
  { id: 'confidence', category: 'Para finalizar', title: 'Em uma escala de 0 a 10, quanto você acredita que conseguirá alcançar esse objetivo?', description: '0 significa “não acredito” e 10 significa “acredito totalmente”.', type: 'scale' },
  { id: 'nutritionPlan', category: 'Plano nutricional', title: 'Quais metas diárias seu nutricionista ou médico definiu para você?', description: 'Preencha apenas as informações que tiver. Elas serão usadas para acompanhar sua evolução no dia a dia.', type: 'nutrition' }
];

let current = 0;
const answers = {};
const elements = {
  category: document.querySelector('#question-category'), title: document.querySelector('#flow-title'), description: document.querySelector('#question-description'), answers: document.querySelector('#answers'), progress: document.querySelector('#progress-bar'), progressText: document.querySelector('#progress-text'), previous: document.querySelector('#previous-button'), next: document.querySelector('#next-button'), form: document.querySelector('#triage-form'), error: document.querySelector('#field-error'), questionScreen: document.querySelector('#question-screen'), completion: document.querySelector('#completion-screen')
};

const firstName = window.triageContext?.firstName?.trim()?.split(' ')[0];
console.info('MetaFit Flow: usuário carregado para o ponto de partida.', window.triageContext?.user);

function apiAnswers() {
  return questions
    .filter(question => Object.prototype.hasOwnProperty.call(answers, question.id))
    .map(question => ({ pergunta: question.title, resposta: answers[question.id] }));
}

function nutritionSuggestionData() {
  return { respostas: apiAnswers() };
}

function activeQuestions() { return questions.filter(question => !question.condition || question.condition(answers)); }
function escapeHtml(value) { return String(value).replace(/[&<>'"]/g, char => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', "'": '&#039;', '"': '&quot;' })[char]); }

function renderOptions(question) {
  const selected = answers[question.id];
  const options = question.type === 'yesno' ? ['Sim', 'Não'] : question.options;
  if (question.type === 'number') return `<label class="number-field"><input type="number" name="answer" inputmode="decimal" min="${question.min}" max="${question.max}" step="${question.step}" value="${selected ?? ''}" placeholder="0"><span>${question.unit}</span></label>`;
  if (question.type === 'date') return `<label class="date-field"><input type="date" name="answer" max="${new Date().toISOString().slice(0, 10)}" value="${selected ?? ''}"></label>`;
  if (question.type === 'text') return `<label class="text-field"><textarea name="answer" rows="4" placeholder="${escapeHtml(question.placeholder)}">${escapeHtml(selected ?? '')}</textarea></label>`;
  if (question.type === 'duration') { const [amount = '', unit = 'meses'] = (selected || '').split(' '); return `<div class="duration-field"><input type="number" name="duration-amount" inputmode="numeric" min="1" max="99" step="1" value="${amount}" placeholder="0"><select name="duration-unit" aria-label="Unidade de prazo"><option value="meses" ${unit === 'meses' ? 'selected' : ''}>meses</option><option value="anos" ${unit === 'anos' ? 'selected' : ''}>anos</option></select></div>`; }
  if (question.type === 'nutrition') { const goals = selected || {}; return `<div class="nutrition-plan">${[['agua','💧 Água','litros/dia','0.1'],['calorias','🔥 Calorias','kcal/dia','1'],['proteinas','🥩 Proteínas','g/dia','1'],['carboidratos','🍞 Carboidratos','g/dia','1'],['gorduras','🥑 Gorduras','g/dia','1']].map(([id,label,unit,step]) => `<label><span>${label}<small>${unit}</small></span><input type="number" name="nutrition-${id}" inputmode="decimal" min="0" step="${step}" value="${goals[id] ?? ''}" placeholder="–"></label>`).join('')}<button class="ai-button" id="ai-suggestion" type="button">✨ Quero uma sugestão com IA</button><p class="ai-notice">A sugestão é informativa e não substitui o acompanhamento de médico ou nutricionista. Consulte um profissional antes de mudar sua alimentação ou tratamento.</p></div>`; }
  if (question.type === 'scale') return `<div class="scale" role="radiogroup" aria-label="Escala de 0 a 10">${Array.from({ length: 11 }, (_, value) => `<label class="scale__option"><input type="radio" name="answer" value="${value}" ${String(selected) === String(value) ? 'checked' : ''}><span>${value}</span></label>`).join('')}</div><div class="scale__labels"><span>Não acredito</span><span>Acredito totalmente</span></div>`;
  return options.map(option => `<label class="answer"><input type="${question.type === 'multi' ? 'checkbox' : 'radio'}" name="answer" value="${escapeHtml(option)}" ${(question.type === 'multi' ? selected?.includes(option) : selected === option) ? 'checked' : ''}><span>${escapeHtml(option)}</span></label>`).join('');
}

function renderQuestion() {
  const active = activeQuestions();
  current = Math.min(current, active.length - 1);
  const question = active[current];
  elements.category.textContent = question.category;
  elements.title.textContent = current === 0 && firstName ? `Olá, ${firstName}. Qual é sua data de nascimento?` : question.title;
  elements.description.textContent = question.description || 'Escolha a opção que melhor representa o seu momento.';
  const progress = Math.round(((current + 1) / active.length) * 100);
  elements.progress.style.width = `${progress}%`;
  elements.progressText.textContent = `${progress}%`;
  elements.previous.hidden = current === 0;
  elements.next.textContent = current === active.length - 1 ? 'Concluir' : 'Continuar';
  elements.error.hidden = true;
  elements.answers.innerHTML = renderOptions(question);
}

function readAnswer(question) {
  if (question.type === 'duration') { const amount = elements.form.querySelector('[name="duration-amount"]').value; const unit = elements.form.querySelector('[name="duration-unit"]').value; if (!amount || Number(amount) < 1) return ''; return `${amount} ${Number(amount) === 1 ? (unit === 'meses' ? 'mês' : 'ano') : unit}`; }
  if (question.type === 'nutrition') return Object.fromEntries(['agua', 'calorias', 'proteinas', 'carboidratos', 'gorduras'].map(id => [id, elements.form.querySelector(`[name="nutrition-${id}"]`).value || null]));
  if (question.type === 'multi') return [...elements.form.querySelectorAll('input:checked')].map(input => input.value);
  const field = elements.form.querySelector('[name="answer"]:checked, [name="answer"]:not([type="radio"]):not([type="checkbox"])');
  return field?.value?.trim() ?? '';
}

function normalizeExclusive(question, value) {
  if (!question.exclusive || !Array.isArray(value)) return value;
  const previous = answers[question.id] || [];
  if (value.includes(question.exclusive) && !previous.includes(question.exclusive)) return [question.exclusive];
  return value.filter(item => item !== question.exclusive || value.length === 1);
}

elements.form.addEventListener('submit', async event => {
  event.preventDefault();
  const question = activeQuestions()[current];
  let value = normalizeExclusive(question, readAnswer(question));
  if (!question.optional && question.type !== 'nutrition' && (!value || (Array.isArray(value) && value.length === 0))) { elements.error.hidden = false; return; }
  answers[question.id] = value;
  const active = activeQuestions();
  if (current === active.length - 1) {
    elements.next.disabled = true;
    elements.next.textContent = 'Enviando...';
    try {
      const response = await fetch(window.location.pathname, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
        body: JSON.stringify({ respostas: apiAnswers() })
      });
      const result = await response.json();
      if (!response.ok || !result.ok) throw new Error('submission_failed');
    } catch (error) {
      console.error('MetaFit Flow: não foi possível enviar o ponto de partida.', error);
      elements.next.disabled = false;
      elements.next.textContent = 'Concluir';
      elements.error.textContent = 'Não foi possível enviar suas respostas. Tente novamente.';
      elements.error.hidden = false;
      return;
    }
    console.info('MetaFit Flow: ponto de partida enviado com sucesso.', apiAnswers());
    elements.questionScreen.hidden = true;
    elements.completion.hidden = false;
    document.querySelector('.progress').hidden = true;
    return;
  }
  current += 1;
  renderQuestion();
});

elements.previous.addEventListener('click', () => { current -= 1; renderQuestion(); });
elements.answers.addEventListener('click', async event => {
  const button = event.target.closest('#ai-suggestion');
  if (!button) return;
  button.disabled = true; button.textContent = 'Gerando sugestão...';
  try {
    const response = await fetch(`${window.location.pathname}/sugestao-plano`, { method: 'POST', headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' }, body: JSON.stringify({ dados: nutritionSuggestionData() }) });
    const result = await response.json();
    if (!response.ok || !result.metas) throw new Error('suggestion_failed');
    Object.entries(result.metas).forEach(([key, value]) => { const input = elements.form.querySelector(`[name="nutrition-${key}"]`); if (input) input.value = value; });
    button.textContent = 'Sugestão aplicada';
  } catch (error) {
    button.disabled = false; button.textContent = 'Tentar sugestão com IA novamente';
    elements.error.textContent = 'Não foi possível gerar a sugestão agora. Você pode preencher os campos manualmente.'; elements.error.hidden = false;
  }
});
renderQuestion();
