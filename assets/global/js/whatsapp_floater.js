"use strict";
document.addEventListener("DOMContentLoaded", function () {
  // Optimized CSS - minified and reduced duplicates
  const cssCode = `@import url('https://fonts.googleapis.com/css?family=Roboto');@keyframes pulse{0%{transform:scale(1)}50%{opacity:.3}100%{transform:scale(1.45);opacity:0}}.pulse{-webkit-animation-name:pulse;animation-name:pulse}.floater-nav-bottom{display:flex;flex-direction:row;justify-content:flex-end;align-content:flex-end;position:fixed;z-index:999;bottom:100px;right:10px;padding:5px}.floater-nav-bottom:hover{transform:translateY(-1px)}@media (max-width:360px){.floater-nav-bottom{width:320px}}.floater-whatsapp-button{display:flex;justify-content:center;align-content:center;width:60px;height:60px;z-index:8;transition:.3s;margin:10px;padding:7px;border:none;outline:none;cursor:pointer;border-radius:50%;background-color:#fff;box-shadow:1px 1px 6px 0 rgba(68,68,68,.705)}.floater-circle-anime{display:flex;position:absolute;justify-content:center;align-content:center;width:60px;height:60px;top:15px;right:15px;border-radius:50%;transition:.3s;background-color:#77bb4a;animation:pulse 1.2s 4s ease 4}.floater-popup-whatsapp{display:none;position:absolute;flex-direction:column;justify-content:flex-start;align-items:flex-start;bottom:85px;right:20px;transition:all .4s ease-in-out;border-radius:10px;background-color:#ece5dd;box-shadow:2px 2px 7px 0 rgba(0,0,0,.705);animation:slideInRight .6s 0s both;z-index:1}@media (max-width:680px){.floater-popup-whatsapp p{font-size:.9em}}.floater-popup-whatsapp>.content-whatsapp-top{display:flex;flex-direction:column}.floater-popup-whatsapp>.content-whatsapp-top p{font-family:Roboto;font-weight:400;font-size:1em}.floater-popup-whatsapp>.floater-content-whatsapp-bottom{display:flex;flex-direction:row}.floater-closePopup{display:flex;justify-content:center;align-items:center;width:28px;height:28px;margin:0 0 15px;border-radius:50%;border:none;outline:none;cursor:pointer;background-color:#f76060;box-shadow:1px 1px 2px 0 rgba(68,68,68,.705)}.floater-closePopup:hover{background-color:#f71d1d;transition:.3s}.floater-send-msPopup{display:flex;justify-content:center;align-items:center;width:40px;height:40px;position:relative;border-radius:50%;background-color:#fff;margin:0 0 0 5px;border:none;outline:none;cursor:pointer;overflow:hidden;box-shadow:1px 1px 2px 0 rgba(68,68,68,.705);z-index:3}img.floater-body-wrap-img{position:absolute;left:0;right:0;width:100%;z-index:-1;bottom:0}.floater-send-msPopup:hover{background-color:#f8f8f8;transition:.3s}.floater-is-active-whatsapp-popup{display:flex;overflow:hidden}input.floater-whats-input[type=text]{width:250px;height:40px;border-radius:5px;box-sizing:border-box;border:1px solid #ddd;font-size:1em;background-color:#fff;padding:0 0 0 10px;transition:all .3s ease-in-out;outline:none}input.floater-whats-input[type=text]:focus{background-color:#f8f8f8;border:1px solid #aaa}@media (max-width:420px){input.floater-whats-input[type=text]{width:225px}}input.floater-whats-input::placeholder{color:rgba(68,68,68,.705);opacity:1}.floater-icon-font-color{color:#fff}.floater-icon-font-color--black{color:#333}.floater-content-whatsapp-top{display:flex;width:100%;cursor:pointer}.floater-header-top-wrapper{display:flex;justify-content:space-between;width:103%;background:#128c7e;align-items:center;border-radius:10px 10px 0 0;margin:-5px 0 0 -5px;padding:15px}.floater-header-top-wrapper button.floater-closePopup{margin:0;background:0 0;box-shadow:none}.floater-header-top-wrapper button.floater-closePopup i{font-size:18px}.floater-header-top-wrapper p{color:#fff!important;font-weight:700}.floater-body-wrap{padding:0 15px;margin-top:300px}.floater-content-whatsapp-bottom{display:flex;padding-bottom:12px;margin-top:18px}.floater-body-wrap p{background:#fff;padding:8px 12px;display:inline-block;border-radius:4px}@font-face{font-family:Material Icons;font-style:normal;font-weight:400;src:url(https://fonts.gstatic.com/s/materialicons/v140/flUhRq6tzZclQEJ-Vdg-IuiaDsNc.woff2) format('woff2')}.floater-material-icons{font-family:Material Icons;font-weight:400;font-style:normal;font-size:24px;line-height:1;letter-spacing:normal;text-transform:none;display:inline-block;white-space:nowrap;word-wrap:normal;direction:ltr;-webkit-font-feature-settings:'liga';-webkit-font-smoothing:antialiased}.floater-p-tag{color:#000!important}`;

  // Create style element and append once
  const styleElement = document.createElement("style");
  styleElement.textContent = cssCode;
  document.head.appendChild(styleElement);

  // Optimized HTML - single template literal
  const container = document.createElement("div");
  container.innerHTML = `
  <div class="floater-nav-bottom">
    <div class="floater-popup-whatsapp fadeIn">
      <div class="floater-content-whatsapp-top">
        <div class="floater-header-top-wrapper">
          <p>Welcome</p>
          <button type="button" class="floater-closePopup">
            <i class="floater-material-icons floater-icon-font-color">close</i>
          </button>
        </div>
      </div>
      <div class="floater-body-wrap">
        <p class="floater-p-tag">Hello! How may we assist you?</p>
        <div class="floater-content-whatsapp-bottom">
          <input class="floater-whats-input" id="whats-in" type="text" placeholder="Send message...">
          <button class="floater-send-msPopup" id="send-btn" type="button">
            <i class="floater-material-icons floater-icon-font-color--black floater-sentBtn">send</i>
          </button>
        </div>
      </div>
    </div>
    <button type="button" id="whats-openPopup" class="floater-whatsapp-button">
      <svg fill="#ffffff" viewBox="-2.45 -2.45 35.57 35.57" stroke="#ffffff" stroke-width="0.122668">
        <path d="M30.667,14.939c0,8.25-6.74,14.938-15.056,14.938c-2.639,0-5.118-0.675-7.276-1.857L0,30.667l2.717-8.017 c-1.37-2.25-2.159-4.892-2.159-7.712C0.559,6.688,7.297,0,15.613,0C23.928,0.002,30.667,6.689,30.667,14.939z M15.61,2.382 c-6.979,0-12.656,5.634-12.656,12.56c0,2.748,0.896,5.292,2.411,7.362l-1.58,4.663l4.862-1.545c2,1.312,4.393,2.076,6.963,2.076 c6.979,0,12.658-5.633,12.658-12.559C28.27,8.016,22.59,2.382,15.61,2.382z M23.214,18.38c-0.094-0.151-0.34-0.243-0.708-0.427 c-0.367-0.184-2.184-1.069-2.521-1.189c-0.34-0.123-0.586-0.185-0.832,0.182c-0.243,0.367-0.951,1.191-1.168,1.437 c-0.215,0.245-0.43,0.276-0.799,0.095c-0.369-0.186-1.559-0.57-2.969-1.817c-1.097-0.972-1.838-2.169-2.052-2.536 c-0.217-0.366-0.022-0.564,0.161-0.746c0.165-0.165,0.369-0.428,0.554-0.643c0.185-0.213,0.246-0.364,0.369-0.609 c0.121-0.245,0.06-0.458-0.031-0.643c-0.092-0.184-0.829-1.984-1.138-2.717c-0.307-0.732-0.614-0.611-0.83-0.611 c-0.215,0-0.461-0.03-0.707-0.03S9.897,8.215,9.56,8.582s-1.291,1.252-1.291,3.054c0,1.804,1.321,3.543,1.506,3.787 c0.186,0.243,2.554,4.062,6.305,5.528c3.753,1.465,3.753,0.976,4.429,0.914c0.678-0.062,2.184-0.885,2.49-1.739 C23.307,19.268,23.307,18.533,23.214,18.38z"></path>
      </svg>
    </button>
    <div class="floater-circle-anime"></div>
  </div>`;
  document.body.appendChild(container);
});

function whatsAppSetup(obj) {
  const whatsIn = document.getElementById("whats-in");
  const whatsappButton = document.querySelector(".floater-whatsapp-button");
  const headerWrapper = document.querySelector(".floater-header-top-wrapper");
  const sentBtn = document.querySelector(".floater-sentBtn");
  const popup = document.querySelector(".floater-popup-whatsapp");

  // Set initial values
  whatsIn.value = obj.message || "";
  whatsappButton.style.backgroundColor = obj.color || "#128c7e";
  headerWrapper.style.backgroundColor = obj.color || "#128c7e";
  sentBtn.style.color = obj.color || "#128c7e";

  // Event listeners
  document.querySelector(".floater-content-whatsapp-top").addEventListener("click", () => {
    popup.classList.toggle("floater-is-active-whatsapp-popup");
  });

  document.querySelector(".floater-whatsapp-button").addEventListener("mouseenter", () => {
    popup.classList.add("floater-is-active-whatsapp-popup");
    popup.style.animation = "fadeIn .6s 0.0s both";
  });

  document.getElementById("send-btn").addEventListener("click", () => {
    const msg = encodeURIComponent(whatsIn.value);
    window.open(`https://wa.me/${obj.mobile}?text=${msg}`, "_blank");
  });
}