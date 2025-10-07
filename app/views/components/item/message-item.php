<?php
function renderMessage($avatar, $content, $time, $isMine = false) {
  if ($isMine) {
    // Tin nhắn của mình (bên phải)
    echo '
    <div class="d-flex mb-3 justify-content-end">
      <div>
        <div class="bg-primary text-white p-2 rounded shadow-sm" style="max-width:70%;">'
          . htmlspecialchars($content) .
        '</div>
        <small class="text-muted d-block text-end">' . htmlspecialchars($time) . '</small>
      </div>
      <img src="' . htmlspecialchars($avatar) . '" class="rounded-circle ms-2" width="40" height="40">
    </div>';
  } else {
    // Tin nhắn của người khác (bên trái)
    echo '
    <div class="d-flex mb-3">
      <img src="' . htmlspecialchars($avatar) . '" class="rounded-circle me-2" width="40" height="40">
      <div>
        <div class="bg-white p-2 rounded shadow-sm border" style="max-width:70%;">'
          . htmlspecialchars($content) .
        '</div>
        <small class="text-muted d-block">' . htmlspecialchars($time) . '</small>
      </div>
    </div>';
  }
}
?>
