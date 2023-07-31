function loadPreviewImage(event, previewId) {
  var imageInput = event.target;
  var previewImage = document.getElementById(previewId);

  if (imageInput.files && imageInput.files[0]) {
    var reader = new FileReader();
    reader.onload = function(e) {
      previewImage.src = e.target.result;
      previewImage.style.display = "block";
    }
    reader.readAsDataURL(imageInput.files[0]);
  } else {
    previewImage.src = "";
    previewImage.style.display = "none";
  }
}

  //show tool tips
  document.addEventListener('DOMContentLoaded', function () {
      var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
      var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
          return new bootstrap.Tooltip(tooltipTriggerEl)
      })
  })  

  // Confirm delete
  document.addEventListener('DOMContentLoaded', function () {
    var deleteModalElement = document.getElementById('deleteModal');
    // Verifica se o elemento 'deleteModal' existe
    if (deleteModalElement) {
      var deleteModal = new bootstrap.Modal(deleteModalElement);
      var deleteConfirmButton = document.getElementById('deleteConfirmButton');
      var deleteButtons = document.querySelectorAll('[data-bs-toggle="modal"][data-bs-target="#deleteModal"]');

      deleteButtons.forEach(function (button) {
        button.addEventListener('click', function (event) {
          var id = event.currentTarget.getAttribute('data-id');
          var mod = event.currentTarget.getAttribute('data-module');
          deleteConfirmButton.href = '/admin/'+mod+'/delete/' + id;
          deleteModal.show();
        });
      });
    }
  });

  function updateSwitchValue(checkboxId, hiddenInputId) {
      const checkbox = document.getElementById(checkboxId);
      const hiddenInput = document.getElementById(hiddenInputId);
      hiddenInput.value = checkbox.checked ? 1 : 0;
  }


  // Validate Google Voice fields
  document.addEventListener('DOMContentLoaded', function () {
    var useGoogleVoice = document.querySelector('input[name="use_google_voice"]'); // Atualizado para input em vez de select
    var googleVoice = document.querySelector('input[name="google_voice"]');
    var googleVoiceLangCode = document.querySelector('input[name="google_voice_lang_code"]');

    // Verifica se o campo use_google_voice existe na página
    if (useGoogleVoice) {
      // Executa a validação quando a página carregar
      validateGoogleVoiceFields();
      
      // Executa a validação novamente quando o campo use_google_voice for alterado
      useGoogleVoice.addEventListener('change', validateGoogleVoiceFields); // Não precisa de alteração aqui
    }

    function validateGoogleVoiceFields() {
      if (useGoogleVoice.checked) { // Atualizado para verificar se está marcado
        googleVoice.required = true;
        googleVoiceLangCode.required = true;
      } else {
        googleVoice.required = false;
        googleVoiceLangCode.required = false;
      }
    }
  });

  document.addEventListener('DOMContentLoaded', function() {
    var formElement = document.querySelector('form');

    if (formElement) {
      formElement.addEventListener('submit', function (event) {
        scrollToFirstInvalidField();
        if (!event.target.checkValidity()) {
          event.preventDefault();
          event.stopPropagation();
          showToast('formErrorToast'); 
        }
        event.target.classList.add('was-validated');
      });
    }
  });

  function showToast(toastId) {
    var toastElement = document.getElementById(toastId);
    if (toastElement) {
      var toast = new bootstrap.Toast(toastElement);
      toast.show();
    }
  }

  // Validate Mic Speak Lang fields
  document.addEventListener('DOMContentLoaded', function () {
    var displayMic = document.querySelector('#floatingDisplayMicrophone');
    var micSpeakLang = document.querySelector('input[name="mic_speak_lang"]');

    // Verifica se o campo display_mic existe na página
    if (displayMic) {
      // Executa a validação quando a página carregar
      validateMicSpeakLangFields();

      // Executa a validação novamente quando o campo display_mic for alterado
      displayMic.addEventListener('change', validateMicSpeakLangFields);
    }

    function validateMicSpeakLangFields() {
      if (displayMic.checked) {
        micSpeakLang.required = true;
      } else {
        micSpeakLang.required = false;
      }
    }
  });


$(document).ready(function () {
    // Selecione os botões pelo class ou ID
    const submitButton = $('.submit-button');
    const btnSaveAbsolute = $('.btn-save-absolute');

    // Função para verificar se o botão de submit está visível na tela
    function isSubmitVisible() {

        if (submitButton.length === 0) {
            return false;
        }
          
        const rect = submitButton[0].getBoundingClientRect();
        const windowHeight = (window.innerHeight || document.documentElement.clientHeight);
        return rect.bottom <= windowHeight;
    }

    // Função para exibir ou ocultar o botão .btn-save-absolute
    function toggleBtnSaveAbsolute() {
        if (isSubmitVisible()) {
            btnSaveAbsolute.fadeOut();
        } else {
            btnSaveAbsolute.fadeIn();
        }
    }

    // Verifique a visibilidade ao carregar a página
    toggleBtnSaveAbsolute();

    // Verifique a visibilidade quando o usuário rolar a página
    $(window).on('scroll', function () {
        toggleBtnSaveAbsolute();
    });
});

$(".btn-save-absolute").on("click", function(){
  $(".submit-button").click()
})

// Função para rolar até o primeiro campo inválido
function scrollToFirstInvalidField() {
    const firstInvalidField = $('.form-control.is-invalid, .was-validated .form-control:invalid').first();
    if (firstInvalidField.length > 0) {
        $('html, body').animate({
            scrollTop: firstInvalidField.offset().top - 100 // Ajuste o valor "100" para adicionar uma margem superior, se necessário
        }, 500); // Ajuste a duração da animação, se necessário
        firstInvalidField.focus();
    }
}


function submitFormTestSMTP() {
  // Obter os elementos do formulário
  const subjectElement = document.getElementById("subject");
  const emailElement = document.getElementById("email");
  const recipientNameElement = document.getElementById("recipient_name");
  const contentElement = document.getElementById("content");

  // Validação dos campos
  let isValid = true;

  if (!subjectElement.value.trim()) {
    subjectElement.classList.add("is-invalid");
    isValid = false;
  } else {
    subjectElement.classList.remove("is-invalid");
  }

  if (!emailElement.value.trim()) {
    emailElement.classList.add("is-invalid");
    isValid = false;
  } else {
    emailElement.classList.remove("is-invalid");
  }

  if (!recipientNameElement.value.trim()) {
    recipientNameElement.classList.add("is-invalid");
    isValid = false;
  } else {
    recipientNameElement.classList.remove("is-invalid");
  }

  if (!validateEmail(emailElement.value)) {
    emailElement.classList.add("is-invalid");
    isValid = false;
  } else {
    emailElement.classList.remove("is-invalid");
  }

  if (!contentElement.value.trim()) {
    contentElement.classList.add("is-invalid");
    isValid = false;
  } else {
    contentElement.classList.remove("is-invalid");
  }

  if (!isValid) {
    scrollToFirstInvalidField();
    showToast('formErrorToast');
    return;
  }

  // Preparar os dados do formulário
  const formData = new FormData();
  formData.append("subject", subjectElement.value);
  formData.append("email", emailElement.value);
  formData.append("content", contentElement.value);
  formData.append("recipient_name", recipientNameElement.value);

  $("#smtp_test_return").html("Wait...");
  $("#btn-test-smtp-email").attr("disabled",true)
  // Enviar os dados via POST para o arquivo PHP
  fetch("../admin/modules/settings/test-smtp.php", {
    method: "POST",
    body: formData
  })
    .then(response => response.text())
    .then(result => {
      // Tratar a resposta do servidor
      console.log(result);
      $("#smtp_test_return").show();
      $("#smtp_test_return").html(result);
      $("#btn-test-smtp-email").attr("disabled",false)
    })
    .catch(error => {
      console.error("Error:", error);
      $("#btn-test-smtp-email").attr("disabled",false)
    });
}

  function validateEmail(email) {
    const re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
  }

document.addEventListener('DOMContentLoaded', function () {
  function onSwitchClick(event) {
    var switchInput = event.target;
    var id = switchInput.id.split('-')[1];
    var status = switchInput.checked ? 1 : 0;

    var formData = new FormData();
    formData.append('id', id);
    formData.append('status', status);
    formData.append('action', 'update_status');

    var myModule = document.getElementById('sortableTableBody').getAttribute('data-module');
    formData.append('myModule', myModule);

    
    $.ajax({
      url: '../admin/modules/update/action.php',
      type: 'POST',
      data: formData,
      processData: false,
      contentType: false,
      success: function (response) {
        var obj = JSON.parse(response);
        toastr.info(obj.message);
      },
      error: function (xhr, status, error) {
        console.log(error);
      }
    });

  }

  // Código do primeiro evento 'DOMContentLoaded'
  var tableBody = document.getElementById('sortableTableBody');
  
  if (typeof Sortable !== 'undefined' && tableBody){
    var tableBody = document.getElementById('sortableTableBody');
    var sortable = Sortable.create(tableBody, {
      animation: 150,
      handle: '.handle',
       scroll: window, // Especificar o elemento de scroll
      scrollSensitivity: 50, // Ajuste a sensibilidade do scroll (distância do cursor até a borda em pixels)
      onEnd: function (evt) {
        var oldIndex = evt.oldIndex;
        var newIndex = evt.newIndex;
        var rows = sortable.el.children;
        var formData = new FormData();
        for (var i = 0; i < rows.length; i++) {
          var row = rows[i];
          formData.append('id[]', row.getAttribute('data-id'));
          formData.append('item_order[]', i);
        }
        var myModule = tableBody.getAttribute('data-module');
        formData.append('myModule', myModule);
        formData.append('action', 'update_order');

        // Usando jQuery AJAX em vez de Fetch
        $.ajax({
          url: '../admin/modules/update/action.php',
          type: 'POST',
          data: formData,
          processData: false,
          contentType: false,
          success: function (response) {
            var obj = JSON.parse(response);
            toastr.info(obj.message);
          },
          error: function (xhr, status, error) {
            console.log(error);
          }
        });

      },
    });

  }  

  // Adicionar o manipulador de eventos a todos os switches na tabela
  var switches = document.querySelectorAll('#sortableTableBody .form-check-input');
  switches.forEach(function (switchInput) {
    switchInput.addEventListener('click', onSwitchClick);
  });
});


function getMessagesJson(thread){
    fetch('../../modules/customers/messages-json.php?thread='+thread)
    .then(response => response.json())
    .then(data => {
        let chatHtml = '';
        data.forEach(value => {
            chatHtml += `<div class="conversation-thread ${value.thread_class}">`;
            if (value.thread_class == 'thread-ai') {
                chatHtml += `<div class="user-image">
                    <img onerror="this.src='/img/no-image.svg'" src="${value.image}" alt="${value.name}" title="${value.name}">
                </div>`;
            }

            chatHtml += `<div class="message-container">
                <div class="message-info">
                    <div class="user-name">
                        <h5>${value.name}</h5>
                    </div>
                    <div class="message-text">
                        <div class="chat-response">${value.message}`;
            if (Array.isArray(value.imageContent)) {                        
              if (value.imageContent) {
                  value.imageContent.map(imageUrl => {
                      chatHtml += `<img src="${imageUrl}" alt="Image content" />`;
                  });
              }
            }
            chatHtml += `</div></div>`;

            chatHtml += `<div class="date-chat">
                        <img src="/img/icon-clock.svg"> ${value.time}
                    </div>
                </div>
            </div>
        </div>`;
        });
        document.getElementById('overflow-chat').innerHTML = chatHtml;
        setTimeout(function() {
          var overflowChat = document.getElementById("overflow-chat");
          overflowChat.scrollTop = overflowChat.scrollHeight;        
        }, 200);        
    })
    .catch((error) => {
        console.error('Error:', error);
    });
}   

//Slugy inputs
window.onload = function() {
    var nameInput = document.getElementById('floatingInputName');
    var slugInput = document.getElementById('floatingInputSlug');

    if (!nameInput && !slugInput) {
        return;
    }

    nameInput.oninput = function() {
        // slugInput.value = slugify(this.value);
    }

    function slugify(text) {
        return text.toString().toLowerCase()
            .replace(/\s+/g, '-')           // Substituir espaços por -
            .replace(/[^\w\-]+/g, '')       // Remover todos os caracteres não palavras
            .replace(/\-\-+/g, '-')         // Substituir múltiplos - por um único -
            .replace(/^-+/, '')             // Trim - do início do texto
            .replace(/-+$/, '');            // Trim - do final do texto
    }
}

function checkOrderDetails(id_order) {
    $("#purchase-details").html(`<div class="spinner-border" role="status"></div>`);
    // URL to which you want to send the request
    var url = '/admin/modules/sales/sales-details.php';

    // Data to be sent
    var data = {id_order: id_order};

    // Request configuration
    var fetchOptions = {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    };

    // Makes the request and handles the response
    fetch(url, fetchOptions)
    .then(response => response.json())
    .then(data => {
        let badgeClass = '';
        if(data.status === 'succeeded') badgeClass = 'bg-success';
        else if(data.status === 'processing') badgeClass = 'bg-primary';
        else badgeClass = 'bg-danger';
        
        let listHtml = `<ul class="list-group">
                            <li class="list-group-item"><strong>ID:</strong> ${data.id_order}</li>
                            <li class="list-group-item"><strong>Name:</strong> ${data.customer_name}</li>
                            <li class="list-group-item"><strong>E-mail:</strong> ${data.customer_email}</li>
                            <li class="list-group-item"><strong>Package:</strong> ${data.item}</li>
                            <li class="list-group-item"><strong>Price:</strong> ${data.price_label}</li>
                            <li class="list-group-item"><strong>Credits:</strong> ${data.credits}</li>
                            <li class="list-group-item"><strong>Date:</strong> ${data.purchase_date}</li>
                            <li class="list-group-item"><strong>Payment Method:</strong> ${data.payment_method}</li>
                            <li class="list-group-item"><strong>Status:</strong> <span class="badge rounded-pill ${badgeClass}">${data.status}</span></li>
                        </ul>`;
        $("#purchase-details").html(listHtml);
    })
    .catch(error => console.error('Error:', error));
}

function prepareOrder(id_order){
  $("#aprovePaymentConfirmButton").attr("data-order",id_order);
}

$("#aprovePaymentConfirmButton").on("click", function(){
  aproveOrder($(this).attr("data-order"));
})

function aproveOrder(id_order) {
    var url = '/admin/modules/sales/action.php';
    var data = {id_order: id_order, action: "aprove_payment"};
    var fetchOptions = {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: new URLSearchParams(data)
    };

    fetch(url, fetchOptions)
    .then(response => response.json())
    .then(data => {
       if(data.status == "success"){
          $(".badge_id_"+id_order).removeClass("bg-danger");
          $(".badge_id_"+id_order).addClass("bg-success");
          $(".badge_id_"+id_order).html("succeeded");
          $(".btn_"+id_order).remove();
       }else{
          console.log("error")
       }
       $('#modalConfirmPayment').modal('hide');

    })
    .catch(error => console.error('Error:', error));
}

  //Show and hide password fields
  document.addEventListener("DOMContentLoaded", function() {
    const togglePasswordIcon = document.querySelector(".toggle-password");

    if (togglePasswordIcon) {
      togglePasswordIcon.addEventListener("click", function() {
        const passwordInput = document.getElementById("floatingPassword");
        const passwordType = passwordInput.getAttribute("type");

        if (passwordType === "password") {
          passwordInput.setAttribute("type", "text");
          togglePasswordIcon.classList.remove("bi-eye-slash");
          togglePasswordIcon.classList.add("bi-eye");
        } else {
          passwordInput.setAttribute("type", "password");
          togglePasswordIcon.classList.remove("bi-eye");
          togglePasswordIcon.classList.add("bi-eye-slash");
        }
      });
    }
  });

  //Password Strength
  document.addEventListener('DOMContentLoaded', function() {
    const passwordInput = document.getElementById('floatingPassword');
    const passwordStrengthBar = document.getElementById('password-strength-bar');
    const passwordStrengthText = document.getElementById('password-strength-text');

    if (passwordInput && passwordStrengthBar && passwordStrengthText) {
      passwordInput.addEventListener('input', function(event) {
        const password = event.target.value;
        const passwordStrength = checkPasswordStrength(password);

        passwordStrengthBar.style.width = passwordStrength.percentage + '%';
        passwordStrengthBar.classList.remove('bg-danger', 'bg-warning', 'bg-success');
        passwordStrengthBar.classList.add(passwordStrength.class);

        passwordStrengthText.textContent = passwordStrength.message;
      });
    }

    function checkPasswordStrength(password) {
      const minLength = 6;
      const strongRegex = new RegExp('^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*])(?=.{8,})');
      const mediumRegex = new RegExp('^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.{6,})');

      if (password.length < minLength) {
        return {
          percentage: 0,
          class: 'bg-danger',
          message: 'The password must have at least 6 characters.'
        };
      }

      if (strongRegex.test(password)) {
        return {
          percentage: 100,
          class: 'bg-success',
          message: 'The password entered is strong!'
        };
      }

      if (mediumRegex.test(password)) {
        return {
          percentage: 50,
          class: 'bg-warning',
          message: 'The password entered is medium.'
        };
      }

      return {
        percentage: 25,
        class: 'bg-danger',
        message: 'The password entered is weak.'
      };
    }
  });

let checkboxes = document.querySelectorAll('.check-language');
checkboxes.forEach((checkbox) => {
  checkbox.addEventListener('change', function() {
    if (this.checked) {
      checkboxes.forEach((otherCheckbox) => {
        if (otherCheckbox !== checkbox) {
          otherCheckbox.checked = false;
        }
      });
    }
  });
});

$(document).on('click', '.addDescription', function() {
    var descriptionField = $('.descriptionField:first').clone();
    descriptionField.find('input').val('');
    $('#descriptionFields').append(descriptionField);
});

$(document).on('click', '.removeDescription', function() {
    if ($('.descriptionField').length > 1) {
        $(this).closest('.descriptionField').remove();
    } else {
        alert('You cannot remove the last description field.');
    }
});  

