import { StrictMode, useEffect, useState } from 'react'
import { createRoot } from 'react-dom/client'
import { BarChart3, BookOpen, Building2, FileText, LogOut, Menu, Users, X } from 'lucide-react'
import './styles.css'

const modules = [
  { key: 'docentes', label: 'Docentes', icon: Users },
  { key: 'estructuras', label: 'Estructuras', icon: Building2 },
  { key: 'nombramientos', label: 'Nombramientos', icon: BookOpen },
  { key: 'usuarios', label: 'Usuarios', icon: Users },
  { key: 'nombramiento-basica', label: 'Nombramientos básica', icon: FileText },
  { key: 'nombramiento-media', label: 'Nombramientos media', icon: FileText },
  { key: 'traslado-basica', label: 'Traslados básica', icon: FileText },
  { key: 'traslado-media', label: 'Traslados media', icon: FileText },
  { key: 'cancelaciones', label: 'Cancelaciones', icon: FileText }
]

async function api(path, options = {}) {
  let response
  try {
    response = await fetch(`/api/index.php${path}`, { credentials: 'include', ...options })
  } catch {
    throw new Error('No se pudo conectar con la API. Inicie Apache/PHP y MySQL antes de ingresar.')
  }
  const raw = await response.text()
  let data
  try {
    data = raw ? JSON.parse(raw) : null
  } catch {
    throw new Error('La API devolvió una respuesta inválida. Verifique que Apache/PHP esté activo.')
  }
  if (!data) {
    throw new Error('La API no respondió. Inicie Apache/PHP y MySQL antes de ingresar.')
  }
  if (!response.ok || !data.ok) throw new Error(data.message || 'No se pudo completar la operación')
  return data
}

function Login({ onLogin }) {
  const [form, setForm] = useState({ usuario: '', password: '' })
  const [error, setError] = useState('')
  const submit = async event => {
    event.preventDefault(); setError('')
    try { onLogin((await api('?action=login', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(form) })).user) }
    catch (e) { setError(e.message) }
  }
  return <main className="login-page"><form className="login-card" onSubmit={submit}>
    <div className="brand-mark">DDE</div><p className="eyebrow">EL PROGRESO · YORO</p>
    <h1>Gestión educativa</h1><p className="muted">Ingresa para continuar</p>
    {error && <div className="alert">{error}</div>}
    <label>Usuario<input value={form.usuario} onChange={e => setForm({ ...form, usuario: e.target.value })} required /></label>
    <label>Contraseña<input type="password" value={form.password} onChange={e => setForm({ ...form, password: e.target.value })} required /></label>
    <button className="primary-button">Iniciar sesión</button>
  </form></main>
}

function TableView({ module }) {
  const [rows, setRows] = useState([]); const [error, setError] = useState('')
  useEffect(() => { api(`?resource=${module.key}`).then(x => setRows(x.data)).catch(e => setError(e.message)) }, [module.key])
  const columns = rows.length ? Object.keys(rows[0]) : []
  return <section className="panel"><div className="panel-heading"><div><p className="eyebrow">MÓDULO</p><h2>{module.label}</h2></div><button className="secondary-button">+ Nuevo registro</button></div>
    {error ? <div className="alert">{error}</div> : <div className="table-wrap"><table><thead><tr>{columns.map(c => <th key={c}>{c.replaceAll('_', ' ')}</th>)}</tr></thead><tbody>{rows.map((row, i) => <tr key={row.id ?? i}>{columns.map(c => <td key={c}>{row[c] ?? '—'}</td>)}</tr>)}</tbody></table>{!rows.length && <p className="empty">No hay registros para mostrar.</p>}</div>}
  </section>
}

function App() {
  const [user, setUser] = useState(null); const [active, setActive] = useState('dashboard'); const [open, setOpen] = useState(false); const [stats, setStats] = useState({})
  useEffect(() => { if (user) api('?resource=dashboard').then(x => setStats(x.data)).catch(() => {}) }, [user])
  if (!user) return <Login onLogin={setUser} />
  const current = modules.find(x => x.key === active)
  const logout = async () => { await api('?action=logout', { method: 'POST' }); setUser(null) }
  return <div className="app-shell"><aside className={open ? 'sidebar open' : 'sidebar'}><div className="sidebar-brand"><span>DDE</span><button onClick={() => setOpen(false)}><X size={20} /></button></div><p className="sidebar-label">NAVEGACIÓN</p><button className={active === 'dashboard' ? 'nav-item active' : 'nav-item'} onClick={() => { setActive('dashboard'); setOpen(false) }}><BarChart3 size={18} /> Resumen</button>{modules.map(({ key, label, icon: Icon }) => <button className={active === key ? 'nav-item active' : 'nav-item'} key={key} onClick={() => { setActive(key); setOpen(false) }}><Icon size={18} /> {label}</button>)}</aside><div className="content"><header className="topbar"><button className="menu-button" onClick={() => setOpen(true)}><Menu /></button><div><p className="eyebrow">DIRECCIÓN DISTRITAL DE EDUCACIÓN</p><h1>El Progreso</h1></div><div className="user-area"><span>{user.nombre || user.usuario}</span><button onClick={logout} title="Cerrar sesión"><LogOut size={18} /></button></div></header><main className="main-content">{active === 'dashboard' ? <><div className="welcome"><div><p className="eyebrow">PANEL PRINCIPAL</p><h2>Buenos días, {user.nombre || user.usuario}</h2><p className="muted">Consulta y administra la información educativa desde un solo lugar.</p></div></div><div className="stat-grid">{[['docentes','Docentes'],['estructuras','Estructuras'],['nombramientos','Nombramientos'],['usuarios','Usuarios']].map(([key,label]) => <div className="stat-card" key={key}><span>{label}</span><strong>{stats[key] ?? '—'}</strong></div>)}</div><div className="quick-grid">{modules.slice(0, 5).map(m => <button key={m.key} onClick={() => setActive(m.key)}><m.icon size={22} /><span>{m.label}</span></button>)}</div></> : <TableView module={current} />}</main></div></div>
}

createRoot(document.getElementById('root')).render(<StrictMode><App /></StrictMode>)
