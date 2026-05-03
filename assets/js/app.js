// ==============================
// 🔥 SAFE HELPERS (NO REDECLARATION)
// ==============================
if (!window.q) window.q = (s, p = document) => p.querySelector(s);
if (!window.qa) window.qa = (s, p = document) => [...p.querySelectorAll(s)];

const input = q('#searchInput');
const locationFilter = q('#locationFilter');
const cards = qa('#eventsGrid .card');
const toastContainer = q('#toast-container');

// ==============================
// 🔥 SUGGESTIONS BOX
// ==============================
let suggestions = q('#suggestions');
if (!suggestions && input) {
  suggestions = document.createElement('div');
  suggestions.id = 'suggestions';
  input.parentNode.appendChild(suggestions);
}

// ==============================
// 🔍 SEARCH + FILTER
// ==============================
if (cards.length) {

  const locations = [...new Set(
    cards.map(c => c.dataset.location).filter(Boolean)
  )].sort();

  if (locationFilter) {
    locations.forEach(loc => {
      locationFilter.insertAdjacentHTML(
        'beforeend',
        `<option value="${loc}">${loc}</option>`
      );
    });
  }

  const filterCards = () => {
    const term = (input?.value || '').toLowerCase().trim();
    const loc = (locationFilter?.value || '').toLowerCase();
    const activeCategory = q('.pill.active')?.dataset.category?.toLowerCase() || 'all';

    cards.forEach(card => {
      const keywords = (card.dataset.keywords || '').toLowerCase();
      const cardLocation = (card.dataset.location || '').toLowerCase();
      const cardCategory = (card.dataset.category || '').toLowerCase();

      const show =
        (!term || keywords.includes(term)) &&
        (!loc || cardLocation === loc) &&
        (activeCategory === 'all' || cardCategory === activeCategory);

      card.style.display = show ? '' : 'none';
      card.style.opacity = show ? '1' : '0';
    });
  };

  input?.addEventListener('input', filterCards);
  locationFilter?.addEventListener('change', filterCards);

  qa('.pill').forEach(btn => {
    btn.addEventListener('click', () => {
      qa('.pill').forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
      filterCards();
    });
  });
}

// ==============================
// 🔥 LIVE SEARCH
// ==============================
let debounceTimer;

input?.addEventListener('input', () => {

  clearTimeout(debounceTimer);
  const query = input.value.trim();

  if (query.length < 2) {
    if (suggestions) suggestions.style.display = "none";
    return;
  }

  debounceTimer = setTimeout(async () => {
    try {
      const res = await fetch(`/evento/pages/search_suggest.php?q=${encodeURIComponent(query)}`);
      const data = await res.json();

      suggestions.innerHTML = "";

      if (!data.length) {
        suggestions.style.display = "none";
        return;
      }

      data.forEach(event => {
        const div = document.createElement("div");
        div.className = "suggestion-item";

        div.innerHTML = `
          <img src="${event.image}" />
          <div>
            <strong>${event.title}</strong><br>
            <small>${event.location}</small>
          </div>
        `;

        div.onclick = () => {
          window.location.href = `/evento/pages/event.php?id=${event.id}`;
        };

        suggestions.appendChild(div);
      });

      suggestions.style.display = "block";

    } catch (err) {
      console.error("Search error:", err);
    }
  }, 300);
});

// ENTER key
input?.addEventListener("keydown", async (e) => {
  if (e.key === "Enter") {
    e.preventDefault();

    const query = input.value.trim();
    if (!query) return;

    try {
      const res = await fetch(`/evento/pages/search_suggest.php?q=${encodeURIComponent(query)}`);
      const data = await res.json();

      if (!data.length) {
        showNoResultModal();
      } else {
        window.location.href = `/evento/pages/event.php?id=${data[0].id}`;
      }

    } catch (err) {
      console.error(err);
    }
  }
});

// Hide suggestions
document.addEventListener("click", (e) => {
  if (suggestions && !suggestions.contains(e.target) && e.target !== input) {
    suggestions.style.display = "none";
  }
});

// ==============================
// 🚨 NO RESULT MODAL
// ==============================
window.showNoResultModal = function () {
  let modal = document.getElementById("noResultModal");

  if (!modal) {
    modal = document.createElement("div");
    modal.id = "noResultModal";
    modal.className = "modal";

    modal.innerHTML = `
      <div class="modal-content">
        <p>❌ Event not available.</p>
        <p>Contact admin for more information.</p>
        <button onclick="closeModal()">OK</button>
      </div>
    `;
    document.body.appendChild(modal);
  }

  modal.style.display = "flex";
};

window.closeModal = function () {
  const modal = document.getElementById("noResultModal");
  if (modal) modal.style.display = "none";
};

// ==============================
// 🔥 GUEST MODAL
// ==============================
window.openGuestModal = function () {
  const modal = document.getElementById("guestModal");
  if (modal) modal.style.display = "flex";
};

window.closeGuestModal = function () {
  const modal = document.getElementById("guestModal");
  if (modal) modal.style.display = "none";
};

// Close modal outside
window.addEventListener("click", (e) => {
  const modal = document.getElementById("guestModal");
  if (e.target === modal) modal.style.display = "none";
});

// ESC close
document.addEventListener("keydown", (e) => {
  if (e.key === "Escape") window.closeGuestModal();
});

// ==============================
// 📂 CV UPLOAD (FIXED PROPERLY)
// ==============================
const cvInputEl = document.getElementById("cvInput");

if (cvInputEl) {

  cvInputEl.addEventListener("change", () => {
    const file = cvInputEl.files[0];
    const box = document.querySelector(".upload-box");

    if (file && box) {

      // 🔥 DO NOT REMOVE INPUT → just add preview
      let preview = box.querySelector(".file-preview");

      if (!preview) {
        preview = document.createElement("div");
        preview.className = "file-preview";
        preview.style.marginTop = "10px";
        box.appendChild(preview);
      }

      preview.innerHTML = `
        <strong>${file.name}</strong><br>
        <small style="color:green;">✔ Ready to upload</small>
      `;
    }
  });

  const form = document.querySelector(".premium-form");

  if (form) {
    form.addEventListener("submit", (e) => {
      if (!cvInputEl.files.length) {
        e.preventDefault();
        alert("Please upload your CV before submitting.");
      }
    });
  }
}

// ==============================
// 🔔 TOAST
// ==============================
qa('.toast-data').forEach(t => {
  if (!toastContainer) return;

  const el = document.createElement('div');
  el.className = `toast ${t.dataset.type || 'info'}`;
  el.textContent = t.dataset.message || '';

  toastContainer.appendChild(el);

  setTimeout(() => el.classList.add('show'), 50);

  setTimeout(() => {
    el.classList.remove('show');
    setTimeout(() => el.remove(), 300);
  }, 3500);
});