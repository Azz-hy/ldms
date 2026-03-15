// ── Auth ──────────────────────────────────────────────────────
const Auth = {
  getToken: () => localStorage.getItem('ldms_token'),
  getUser:  () => JSON.parse(localStorage.getItem('ldms_user') || 'null'),
  setAuth:  (t, u) => { localStorage.setItem('ldms_token', t); localStorage.setItem('ldms_user', JSON.stringify(u)); },
  clear:    () => { localStorage.removeItem('ldms_token'); localStorage.removeItem('ldms_user'); },
  isLogged: () => !!localStorage.getItem('ldms_token'),
};

// ── Fetch wrapper ─────────────────────────────────────────────
async function api(method, path, body = null) {
  const headers = { 'Content-Type': 'application/json', 'Accept': 'application/json' };
  const token = Auth.getToken();
  if (token) headers['Authorization'] = `Bearer ${token}`;
  const opts = { method, headers };
  if (body) opts.body = JSON.stringify(body);
  const res = await fetch(`${window.LDMS_API_URL || 'http://localhost:8000/api'}${path}`, opts);
  if (res.status === 401) { Auth.clear(); window.location.href = '/index.html'; return; }
  const data = await res.json();
  if (!res.ok) throw data;
  return data;
}

const GET    = (path)       => api('GET',    path);
const POST   = (path, body) => api('POST',   path, body);
const PUT    = (path, body) => api('PUT',    path, body);
const DELETE = (path)       => api('DELETE', path);

// ── Require auth ──────────────────────────────────────────────
function requireAuth(role) {
  if (!Auth.isLogged()) { window.location.href = '../index.html'; return null; }
  const user = Auth.getUser();
  if (role && user.role !== role) { window.location.href = '../index.html'; return null; }
  return user;
}

// ── Helpers ───────────────────────────────────────────────────
function statusBadge(s) {
  const labels = { pending:'Pending', assigned:'Assigned', picked_up:'Picked Up', on_the_way:'On The Way', delivered:'Delivered', failed:'Failed' };
  return `<span class="badge-status badge-${s}">${labels[s] || s}</span>`;
}

function timeAgo(d) {
  const m = Math.floor((Date.now() - new Date(d)) / 60000);
  if (m < 1) return 'just now';
  if (m < 60) return `${m} minutes ago`;
  const h = Math.floor(m / 60);
  if (h < 24) return `${h} hours ago`;
  return `${Math.floor(h/24)} days ago`;
}

function fmtDate(d) {
  if (!d) return '—';
  return new Date(d).toLocaleDateString('en-GB', { day:'numeric', month:'short', year:'numeric' });
}

function fmtMoney(n) { return '$' + parseFloat(n || 0).toFixed(2); }

function toast(msg, type = 'success') {
  const colors = { success: '#dcfce7', error: '#fee2e2', info: '#dbeafe' };
  const textColors = { success: '#166534', error: '#991b1b', info: '#1e40af' };
  const icons = { success: 'check-circle', error: 'exclamation-circle', info: 'info-circle' };
  const el = document.createElement('div');
  el.style.cssText = `position:fixed;top:1rem;right:1rem;z-index:9999;background:${colors[type]};color:${textColors[type]};border-radius:10px;padding:.75rem 1.25rem;font-size:.875rem;font-weight:500;box-shadow:0 4px 15px rgba(0,0,0,.1);display:flex;align-items:center;gap:.5rem;max-width:320px;font-family:'Plus Jakarta Sans',sans-serif`;
  el.innerHTML = `<i class="bi bi-${icons[type]}"></i>${msg}`;
  document.body.appendChild(el);
  setTimeout(() => el.remove(), 3500);
}
