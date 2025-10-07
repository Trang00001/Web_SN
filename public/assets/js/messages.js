document.addEventListener("DOMContentLoaded", () => {
  const sendBtn = document.getElementById("send-btn");
  const input = document.getElementById("message-input");
  const chatBox = document.getElementById("chat-box");

  function sendMessage() {
    const text = input.value.trim();
    if (!text) return;

    // Tạo phần tử tin nhắn mới (bên phải)
    const message = document.createElement("div");
    message.classList.add("d-flex", "mb-3", "justify-content-end");
    message.innerHTML = `
      <div>
        <div class="bg-primary text-white p-2 rounded shadow-sm" style="max-width:70%;">${text}</div>
        <small class="text-muted d-block text-end">Vừa xong</small>
      </div>
      <img src="https://i.pravatar.cc/40?img=5" class="rounded-circle ms-2" width="40" height="40">
    `;
    chatBox.appendChild(message);

    // Cuộn xuống cuối và xóa input
    chatBox.scrollTop = chatBox.scrollHeight;
    input.value = "";
  }

  // Bấm nút gửi
  sendBtn.addEventListener("click", sendMessage);

  // Nhấn Enter để gửi
  input.addEventListener("keydown", (e) => {
    if (e.key === "Enter") {
      e.preventDefault();
      sendMessage();
    }
  });
});
