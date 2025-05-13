// custom-alert-modal.js
window.addEventListener('DOMContentLoaded', function () {
    const message = window.customAlertMessage || "🚨 You have unpaid dues!";
    const imageUrl = window.customAlertImage || "https://cdn-icons-png.flaticon.com/512/1828/1828843.png";

    const modalOverlay = document.createElement('div');
    modalOverlay.style.cssText = `
      position: fixed;
      top: 0; left: 0;
      width: 100vw; height: 100vh;
      background: rgba(0, 0, 0, 0.5);
      display: flex;
      align-items: center;
      justify-content: center;
      z-index: 10000;
    `;

    const modalBox = document.createElement('div');
    modalBox.style.cssText = `
      background: #fff;
      padding: 25px 30px;
      border-radius: 10px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.3);
      text-align: center;
      max-width: 420px;
      font-family: sans-serif;
    `;

    modalBox.innerHTML = `
      <img src="${imageUrl}" alt="Alert" style="width: 60px; margin-bottom: 15px;">
      <div style="font-size: 18px; margin-bottom: 25px; color: #333;">
        ${message}
      </div>
      <button id="custom-alert-ok" style="
        padding: 10px 24px;
        background: #007BFF;
        color: white;
        border: none;
        border-radius: 5px;
        font-size: 16px;
        cursor: pointer;
      ">OK</button>
    `;

    modalOverlay.appendChild(modalBox);
    document.body.appendChild(modalOverlay);

    document.getElementById('custom-alert-ok').addEventListener('click', () => {
        modalOverlay.remove();
    });
});
