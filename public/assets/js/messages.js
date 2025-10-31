document.addEventListener("DOMContentLoaded", function () {
  const input = document.getElementById("message-input");
  const btn = document.getElementById("send-btn");
  const chatBox = document.getElementById("chat-box");

  function scrollBottom() {
    chatBox.scrollTop = chatBox.scrollHeight;
  }

  function appendMessage(msg) {
    const isMe = msg.SenderID == userID;
    const wrapper = document.createElement("div");
    wrapper.className = "d-flex mb-3 " + (isMe ? "justify-content-end" : "");
    wrapper.innerHTML = `
      ${
        !isMe
          ? `<img src="${
              msg.AvatarURL || "https://i.pravatar.cc/40"
            }" class="rounded-circle me-2" width="40" height="40">`
          : ""
      }
      <div>
        <div class="${
          isMe ? "bg-primary text-white" : "bg-white"
        } p-2 rounded shadow-sm">
          ${msg.Content}
        </div>
        <small class="text-muted d-block ${isMe ? "text-end" : ""}">${
      msg.SentTime
    }</small>
      </div>
    `;
    chatBox.appendChild(wrapper);
    scrollBottom();
  }

  btn.addEventListener("click", function () {
    const content = input.value.trim();
    if (!content) return;

    fetch("/app/controllers/MessageController.php", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: `action=send&chatID=${chatBoxID}&content=${encodeURIComponent(
        content
      )}`,
    })
      .then((res) => res.json())
      .then((data) => {
        if (data.success) {
          input.value = "";
          appendMessage(data.message); // thêm message vừa gửi
        } else {
          alert(data.error || "Gửi thất bại!");
        }
      });
  });

  input.addEventListener("keypress", function (e) {
    if (e.key === "Enter") btn.click();
  });
});
