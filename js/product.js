// js/product.js

// Small toast popup
function toast(message, type = "info") {
    const box = document.createElement("div");
    box.textContent = message;
    box.style.position = "fixed";
    box.style.bottom = "30px";
    box.style.right = "30px";
    box.style.padding = "14px 20px";
    box.style.background = type === "success" ? "#2ecc71" : "#e74c3c";
    box.style.color = "#fff";
    box.style.borderRadius = "8px";
    box.style.boxShadow = "0 4px 12px rgba(0,0,0,.2)";
    box.style.zIndex = 99999;
    document.body.appendChild(box);
    setTimeout(() => box.remove(), 2800);
}

// DELETE PRODUCT (AJAX)
document.addEventListener("click", async (e) => {
    if (!e.target.classList.contains("ajax-delete-product")) return;

    if (!confirm("Are you sure you want to delete this product?")) return;

    const pid = e.target.dataset.id;

    let fd = new FormData();
    fd.append("product_id", pid);

    try {
        let res = await fetch("actions/delete_product_action.php", {
            method: "POST",
            body: fd
        });
        let data = await res.json();
        
        if (data.status === "success") {
            toast("Product deleted", "success");
            document.querySelector(`tr[data-id='${pid}']`)?.remove();
            document.querySelector(`#p-${pid}`)?.remove(); // for product cards
        } else {
            toast(data.message, "error");
        }
    } catch (err) {
        toast("Network error. Try again.", "error");
    }
});

// QUICK ADD TO CART (Marketplace)
document.addEventListener("click", async (e) => {
    if (!e.target.classList.contains("quick-add")) return;

    const pid = e.target.dataset.id;
    let fd = new FormData();
    fd.append("product_id", pid);
    fd.append("quantity", 1);

    try {
        let res = await fetch("actions/add_to_cart_action.php", {
            method: "POST",
            body: fd
        });
        let j = await res.json();

        if (j.status === "success") {
            toast("Added to cart", "success");
        } else {
            toast(j.message, "error");
        }
    } catch (err) {
        toast("Network error", "error");
    }
});
