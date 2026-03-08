import { useEffect, useMemo, useState } from 'react';
import { z } from 'zod';
import api from '../api/client';

const entities = [
  'countries','states','cities','companies','users','sessions','roles','role-permissions','login-history','password-reset-tokens','code-headers','code-details','brands','features','models','item-details','item-images','item-features'
];

const idSchema = z.object({ id: z.coerce.number().int().positive() }).passthrough();

export default function MastersPage() {
  const [entity, setEntity] = useState('countries');
  const [rows, setRows] = useState([]);
  const [form, setForm] = useState('{}');
  const [message, setMessage] = useState('');

  const load = async () => {
    const { data } = await api.get(`/masters/${entity}`);
    setRows(data);
  };

  useEffect(() => { load(); setForm('{}'); setMessage(''); }, [entity]);

  const columns = useMemo(() => rows.length ? Object.keys(rows[0]) : [], [rows]);

  const parseJson = () => {
    try { return JSON.parse(form); }
    catch { throw new Error('Invalid JSON payload'); }
  };

  return (
    <div className="space-y-4">
      <h2 className="text-xl font-bold">Master Data CRUD</h2>
      <div className="bg-white dark:bg-slate-900 dark:border dark:border-slate-700 p-3 rounded shadow flex flex-wrap gap-2">
        {entities.map((e) => (
          <button key={e} onClick={() => setEntity(e)} className={`px-3 py-1 rounded text-sm ${entity === e ? 'bg-blue-600 text-white' : 'bg-slate-200 dark:bg-slate-700'}`}>
            {e}
          </button>
        ))}
      </div>

      <div className="grid md:grid-cols-2 gap-4">
        <div className="bg-white dark:bg-slate-900 dark:border dark:border-slate-700 p-4 rounded shadow">
          <h3 className="font-semibold mb-2">Create / Update JSON Payload ({entity})</h3>
          {message ? <p className="text-sm mb-2 text-blue-600 dark:text-blue-400">{message}</p> : null}
          <textarea value={form} onChange={(e) => setForm(e.target.value)} className="w-full h-48 border p-2 font-mono text-xs bg-white dark:bg-slate-800" />
          <div className="mt-3 flex gap-2">
            <button className="bg-blue-600 text-white px-3 py-2 rounded" onClick={async () => { try { const payload = parseJson(); await api.post(`/masters/${entity}`, payload); setMessage('Created'); await load(); } catch (e) { setMessage(e.message); } }}>Create</button>
            <button className="bg-amber-600 text-white px-3 py-2 rounded" onClick={async () => { try { const payload = idSchema.parse(parseJson()); await api.put(`/masters/${entity}/${payload.id}`, payload); setMessage('Updated'); await load(); } catch (e) { setMessage(e.message); } }}>Update by id</button>
            <button className="bg-red-600 text-white px-3 py-2 rounded" onClick={async () => { try { const payload = idSchema.parse(parseJson()); await api.delete(`/masters/${entity}/${payload.id}`); setMessage('Deleted'); await load(); } catch (e) { setMessage(e.message); } }}>Delete by id</button>
          </div>
          <p className="text-xs text-slate-500 mt-2">Use payload format: {'{"id": 1, "Field": "Value"}'}</p>
        </div>

        <div className="bg-white dark:bg-slate-900 dark:border dark:border-slate-700 p-4 rounded shadow overflow-auto">
          <h3 className="font-semibold mb-2">{entity} Records</h3>
          <table className="min-w-full text-xs">
            <thead><tr>{columns.map((c) => <th key={c} className="border p-1 text-left">{c}</th>)}</tr></thead>
            <tbody>{rows.map((r, i) => <tr key={i}>{columns.map((c) => <td key={c} className="border p-1">{String(r[c] ?? '')}</td>)}</tr>)}</tbody>
          </table>
        </div>
      </div>
    </div>
  );
}
