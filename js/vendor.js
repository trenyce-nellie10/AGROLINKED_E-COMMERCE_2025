// js/vendor.js

function toast(msg, type = "info") {
    const box = document.createElement("div");
    box.textContent = msg;
    box.style.position = "fixed";
    box.style.bottom = "30px";
    box.style.right = "30px";
    box.style.padding = "14px 20px";
    box.style.background = type === "success" ? "#27ae60" : "#c0392b";
    box.style.color = "#fff";
    box.style.borderRadius = "8px";
    box.style.boxShadow = "0 4px 12px rgba(0,0,0,0.25)";
    box.style.zIndex = 99999;
    document.body.appendChild(box);
    setTimeout(() => box.remove(), 3000);
}

// Vendor approval (admin only)
document.addEventListener("click", async (e) => {
    if (!e.target.classList.contains("approve-vendor")) return;

    const row = e.target.closest("tr");
    const vendorId = row.dataset.id;

    e.target.disabled = true;
    e.target.textContent = "Approving...";

    let fd = new FormData();
    fd.append("vendor_id", vendorId);

    try {
        let res = await fetch("actions/vendor_approve_action.php", {
            method: "POST",
            body: fd
        });
        let data = await res.json();

        if (data.status === "success") {
            toast("Vendor approved!", "success");
            row.querySelector(".status-cell").innerHTML =
                '<span class="approved-tag">Approved</span>';
            e.target.className = "btn btn-secondary";
            e.target.textContent = "Approved";
        } else {
            toast(data.message, "error");
            e.target.disabled = false;
            e.target.textContent = "Approve";
        }
    } catch (err) {
        toast("Network error", "error");
        e.target.disabled = false;
        e.target.textContent = "Approve";
    }
});

// FORM VALIDATION HELPERS
function validatePrice(input) {
    input.value = input.value.replace(/[^0-9.]/g, "");
}

function validateQty(input) {
    input.value = input.value.replace(/[^0-9]/g, "");
}

// Autosave alerts (optional enhancement)
document.addEventListener("input", (e) => {
    if (e.target.closest("#addProductForm") || e.target.closest("#editProductForm")) {
        const msg = document.getElementById("autoSaveMsg");
        if (!msg) return;

        msg.style.color = "#999";
        msg.textContent = "Changes not saved";
    }
});
