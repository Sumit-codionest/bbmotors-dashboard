export default function DataTable({ columns, rows }) {
  return (
    <div className="overflow-auto bg-white dark:bg-slate-900 dark:border dark:border-slate-700 rounded shadow">
      <table className="min-w-full text-sm">
        <thead className="bg-slate-100 dark:bg-slate-800"><tr>{columns.map(c => <th key={c.key} className="p-2 text-left">{c.label}</th>)}</tr></thead>
        <tbody>{rows.map((r, i) => <tr key={i} className="border-t border-slate-200 dark:border-slate-700">{columns.map(c => <td key={c.key} className="p-2">{r[c.key]}</td>)}</tr>)}</tbody>
      </table>
    </div>
  );
}
