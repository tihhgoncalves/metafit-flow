const questions = [
  { category: 'Vamos começar', title: 'Quero conhecer você melhor.', description: 'Essas respostas nos ajudam a preparar uma experiência mais adequada para o seu momento.', options: ['Quero emagrecer', 'Quero ganhar massa muscular', 'Quero ter mais saúde e disposição', 'Tenho outro objetivo'] },
  { category: 'Sua rotina', title: 'Como está sua relação com a atividade física?', description: 'Não existe resposta certa. Queremos só entender o seu ponto de partida.', options: ['Ainda não faço exercícios', 'Faço de vez em quando', 'Tenho uma rotina frequente', 'Prefiro não responder'] },
  { category: 'Seu momento', title: 'O que mais dificulta manter uma rotina?', description: 'Selecione a opção que melhor representa seu momento atual.', options: ['Falta de tempo', 'Não sei por onde começar', 'Dificuldade em manter a constância', 'Nenhuma dessas opções'] },
  { category: 'Para finalizar', title: 'Como prefere seguir com o MetaFit?', description: 'Vamos usar isso para deixar a próxima conversa mais útil para você.', options: ['Receber orientações práticas', 'Entender melhor como o serviço funciona', 'Conversar com um especialista', 'Ainda estou conhecendo'] }
];

const firstName = window.triageContext?.firstName?.trim()?.split(' ')[0];
console.info('MetaFit Flow: usuário carregado para a triagem.', window.triageContext?.user);
if (firstName) {
  questions[0].title = `Olá, ${firstName}. Quero conhecer você melhor.`;
}

let current = 0;
const responses = {};
const elements = {
  category: document.querySelector('#question-category'), title: document.querySelector('#flow-title'), description: document.querySelector('#question-description'), answers: document.querySelector('#answers'), progress: document.querySelector('#progress-bar'), progressText: document.querySelector('#progress-text'), previous: document.querySelector('#previous-button'), next: document.querySelector('#next-button'), form: document.querySelector('#triage-form'), error: document.querySelector('#field-error'), questionScreen: document.querySelector('#question-screen'), completion: document.querySelector('#completion-screen')
};

function renderQuestion() {
  const question = questions[current];
  elements.category.textContent = question.category;
  elements.title.textContent = question.title;
  elements.description.textContent = question.description;
  elements.progress.style.width = `${((current + 1) / questions.length) * 100}%`;
  elements.progressText.textContent = `${current + 1} de ${questions.length}`;
  elements.previous.hidden = current === 0;
  elements.next.textContent = current === questions.length - 1 ? 'Concluir' : 'Continuar';
  elements.error.hidden = true;
  elements.answers.innerHTML = question.options.map((option, index) => `<label class="answer"><input type="radio" name="answer" value="${index}" ${responses[current] === index ? 'checked' : ''}><span>${option}</span></label>`).join('');
}

elements.form.addEventListener('submit', (event) => {
  event.preventDefault();
  const selected = elements.form.querySelector('input[name="answer"]:checked');
  if (!selected) { elements.error.hidden = false; return; }
  responses[current] = Number(selected.value);
  if (current === questions.length - 1) {
    // Futuramente, as respostas serão enviadas para a API aqui.
    elements.questionScreen.hidden = true;
    elements.completion.hidden = false;
    document.querySelector('.progress').hidden = true;
    return;
  }
  current += 1;
  renderQuestion();
});

elements.previous.addEventListener('click', () => { current -= 1; renderQuestion(); });
renderQuestion();
