var addSummaryElement = document.querySelector('.add-summary');
var summaryTableElement = document.querySelector('.summary-table');

// Подстветка статусов на элементах списка
var summaryElement = summaryTableElement.querySelectorAll('.summary-item');
summaryElement.forEach(element => {
  statusElement = element.querySelector('.status');
  console.log(statusElement);
  console.log(statusElement.dataset.status);
  if (statusElement.dataset.status == 3) {
    statusElement.style.color = 'green';
  }
  if (statusElement.dataset.status == 4) {
    statusElement.style.color = 'red';
  }
});

// Создание записи

// addSummaryElement.addEventListener('click', evt => {
//   evt.preventDefault();
//   openModal(1);
// });

// Редактирование подробного и краткого описания
var detailEditElement = summaryTableElement.querySelectorAll('.detail-edit');
var summaryEditElement = summaryTableElement.querySelectorAll('.summary-edit');

detailEditElement.forEach(element => {
  element.addEventListener('click', evt => {
    openModal(element);
  });
});


summaryEditElement.forEach(element => {
  element.addEventListener('click', evt => {
    openModal(element);
  });
});

var openModal = (element) => {
  console.log(element);
  if (element.className === 'detail') {
    $('#summaryModal.modal-title').textContent = 'Подробное описание';
  }
  if (element.className === 'summary') {
  }
  $('#summaryModal').modal('show');
  // var modalElement = document.querySelector('#summaryModal');
  // modalElement.show();
};



// // получим кнопку id="btn" с помощью которой будем открывать модальное окно
// const btn = document.querySelector('#btn');
// // активируем контент id="modal" как модальное окно
// const modal = new bootstrap.Modal(document.querySelector('#modal'));
// // при нажатии на кнопку
// btn.addEventListener('click', function () {
//   // открываем модальное окно
//   modal.show();
// });

// var exampleModal = document.getElementById('exampleModal');
// console.log(exampleModal);
// exampleModal.addEventListener('show.bs.modal', function (event) {
//   console.log(exampleModal);
//   // Кнопка, запускающая модальное окно
//   var button = event.relatedTarget
//   // Извлечь информацию из атрибутов data-bs- *
//   var recipient = button.getAttribute('data-bs-whatever')
//   // При необходимости вы можете инициировать запрос AJAX здесь
//   // а затем выполните обновление в обратном вызове.
//   //
//   // Обновите содержимое модального окна.
//   var modalTitle = exampleModal.querySelector('.modal-title')
//   var modalBodyInput = exampleModal.querySelector('.modal-body input')

//   modalTitle.textContent = 'Новое сообщение для ' + recipient
//   modalBodyInput.value = recipient
// });