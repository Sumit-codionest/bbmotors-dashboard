import { useForm } from 'react-hook-form';
import { z } from 'zod';
import { zodResolver } from '@hookform/resolvers/zod';
import { useAuth } from '../context/AuthContext';
import { useNavigate } from 'react-router-dom';

const schema = z.object({ username: z.string().min(3), password: z.string().min(6) });
export default function LoginPage() {
  const { login } = useAuth();
  const nav = useNavigate();
  const { register, handleSubmit } = useForm({ resolver: zodResolver(schema) });
  return <div className="min-h-screen grid place-items-center"><form className="bg-white p-8 rounded shadow w-96" onSubmit={handleSubmit(async v => { await login(v.username, v.password); nav('/'); })}><h2 className="text-xl mb-4">Login</h2><input className="w-full border p-2 mb-3" placeholder="Username" {...register('username')} /><input className="w-full border p-2 mb-3" type="password" placeholder="Password" {...register('password')} /><button className="w-full bg-blue-600 text-white py-2 rounded">Sign In</button></form></div>;
}
