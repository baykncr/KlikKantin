// --- Data sementara (nantinya diganti dari database) ---
const canteens = [
  { id: 1, name: "Kantin A", image: "img/kantinA.jpg", desc: "Kantin depan gedung utama" },
  { id: 2, name: "Kantin B", image: "img/kantinB.jpg", desc: "Kantin dekat lapangan" },
  { id: 3, name: "Kantin C", image: "img/kantinC.jpg", desc: "Kantin belakang perpustakaan" }
];

const menus = [
  { canteen_id: 1, name: "Nasi Goreng", price: 15000, image: "img/nasgor.jpg" },
  { canteen_id: 1, name: "Ayam Geprek", price: 18000, image: "img/geprek.jpg" },
  { canteen_id: 2, name: "Mie Ayam", price: 12000, image: "img/mieayam.jpg" },
];

// --- Tampilkan daftar kantin di homepage ---
const canteenList = document.getElementById("canteenList");
if (canteenList) {
  canteens.forEach(c => {
    const div = document.createElement("div");
    div.classList.add("canteen-card");
    div.innerHTML = `
      <img src="${c.image}" alt="${c.name}">
      <h3>${c.name}</h3>
      <p>${c.desc}</p>
    `;
    div.onclick = () => {
      window.location.href = `canteen.html?id=${c.id}`;
    };
    canteenList.appendChild(div);
  });
}

// --- Halaman detail kantin ---
const urlParams = new URLSearchParams(window.location.search);
const canteenId = urlParams.get("id");

if (canteenId) {
  const canteen = canteens.find(c => c.id == canteenId);
  if (canteen) {
    document.getElementById("canteenName").innerText = canteen.name;

    const menuList = document.getElementById("menuList");
    const filtered = menus.filter(m => m.canteen_id == canteenId);
    filtered.forEach(m => {
      const div = document.createElement("div");
      div.classList.add("menu-item");
      div.innerHTML = `
        <img src="${m.image}">
        <h4>${m.name}</h4>
        <p>Rp${m.price}</p>
      `;
      menuList.appendChild(div);
    });
  }

  // Rating bintang interaktif
  const stars = document.querySelectorAll("#starRating span");
  stars.forEach(star => {
    star.addEventListener("click", () => {
      stars.forEach(s => s.classList.remove("active"));
      star.classList.add("active");
      const value = star.dataset.value;
      console.log("Rating:", value);
    });
  });

  // Simulasi submit form rating
  document.getElementById("ratingForm").addEventListener("submit", e => {
    e.preventDefault();
    alert("Rating berhasil dikirim (nanti disimpan ke database)!");
  });
}
