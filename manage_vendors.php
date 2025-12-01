<?php
// manage_vendors.php
require_once "core.php";
require_once "controllers/user_controller.php";
require_once "controllers/vendor_controller.php";

if (!is_logged_in() || ($_SESSION['role'] ?? 0) != 1) {
    header("Location: login.php");
    exit();
}

$vendors = get_all_vendors_ctr();
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Manage Vendors - AgroLinked</title>
  <link rel="stylesheet" href="css/style.css">
  <style>
    .styled-table{width:100%;border-collapse:collapse}
    .styled-table th, .styled-table td{padding:10px;border-bottom:1px solid #eee;text-align:left}
    .approved-tag{background:#e8f8ef;color:#2a8a4b;padding:6px 8px;border-radius:8px;font-weight:600}
    .pending-tag{background:#fff5e6;color:#b86f00;padding:6px 8px;border-radius:8px;font-weight:600}
  </style>
</head>
<body class="page">
  <?php include 'nav.php'; ?>
  <main class="center-wrap" style="align-items:flex-start;padding:36px 20px;">
    <div style="width:100%; max-width:1100px;">
      <h1>Vendor Applications</h1>
      <p class="muted">Approve or reject vendor signup requests.</p>

      <table class="styled-table" id="vendorsTable">
        <thead>
          <tr>
            <th>#</th>
            <th>Business</th>
            <th>Vendor</th>
            <th>Contact</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($vendors as $i => $v): ?>
          <tr data-id="<?php echo $v['user_id']; ?>">
            <td><?php echo $i+1; ?></td>
            <td><?php echo htmlspecialchars($v['business_name'] ?? '-'); ?></td>
            <td><?php echo htmlspecialchars($v['full_name'] . " <" . $v['email'] . ">"); ?></td>
            <td><?php echo htmlspecialchars($v['phone'] ?? '-'); ?></td>
            <td class="status-cell"><?php echo $v['approved'] ? '<span class="approved-tag">Approved</span>' : '<span class="pending-tag">Pending</span>'; ?></td>
            <td>
              <?php if (!$v['approved']): ?>
                <button class="btn btn-primary approve-btn">Approve</button>
              <?php else: ?>
                <button class="btn btn-secondary" disabled>Approved</button>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>

    </div>
  </main>

<script>
document.addEventListener('click', async (e) => {
  if (!e.target.matches('.approve-btn')) return;
  const btn = e.target;
  btn.disabled = true;
  btn.textContent = 'Approving...';
  const tr = btn.closest('tr');
  const vendorId = tr.dataset.id;
  try {
    const fd = new FormData();
    fd.append('vendor_id', vendorId);
    const res = await fetch('actions/vendor_approve_action.php', { method: 'POST', body: fd });
    const j = await res.json();
    if (j.status === 'success') {
      tr.querySelector('.status-cell').innerHTML = '<span class="approved-tag">Approved</span>';
      btn.textContent = 'Approved';
      btn.className = 'btn btn-secondary';
    } else {
      alert(j.message || 'Error');
      btn.disabled = false;
      btn.textContent = 'Approve';
    }
  } catch (err) {
    alert('Network error');
    btn.disabled = false;
    btn.textContent = 'Approve';
  }
});
</script>
</body>
</html>
