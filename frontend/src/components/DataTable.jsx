export default function DataTable({ columns, rows }) {
  return (
    <div className="overflow-auto bg-white rounded shadow">
      <table className="min-w-full text-sm">
        <thead className="bg-slate-100"><tr>{columns.map(c => <th key={c.key} className="p-2 text-left">{c.label}</th>)}</tr></thead>
        <tbody>{rows.map((r, i) => <tr key={i} className="border-t">{columns.map(c => <td key={c.key} className="p-2">{r[c.key]}</td>)}</tr>)}</tbody>
      </table>
    </div>
  );
}
