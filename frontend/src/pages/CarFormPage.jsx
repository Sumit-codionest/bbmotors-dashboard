import { useForm } from 'react-hook-form';
import api from '../api/client';

export default function CarFormPage() {
  const { register, handleSubmit } = useForm();
  return <form className="bg-white p-4 rounded shadow grid md:grid-cols-3 gap-3" onSubmit={handleSubmit(async (v) => { await api.post('/cars', v); alert('Car created'); })}>
    <input className="border p-2" placeholder="company_id" {...register('company_id')} />
    <input className="border p-2" placeholder="model_id" {...register('model_id')} />
    <input className="border p-2" placeholder="registration_no" {...register('registration_no')} />
    <input className="border p-2" placeholder="make_year" {...register('make_year')} />
    <input className="border p-2" placeholder="registration_year" {...register('registration_year')} />
    <input className="border p-2" placeholder="km_driven" {...register('km_driven')} />
    <input className="border p-2" placeholder="price" {...register('price')} />
    <input className="border p-2" placeholder="color_code" {...register('color_code')} />
    <input className="border p-2" placeholder="status_code" {...register('status_code')} />
    <input className="border p-2" placeholder="fuel_type_code" {...register('fuel_type_code')} />
    <input className="border p-2" placeholder="transmission_code" {...register('transmission_code')} />
    <button className="bg-blue-600 text-white px-3 py-2 rounded">Save</button>
  </form>;
}
