import AdminScreen from '@/Layouts/AdminScreen';
import { Head } from '@inertiajs/react';

export default function Dashboard(props) {
    return (
        <AdminScreen {...props}>
            <Head title="Dashboard Admin" />

            <h1>Dashboard do admin</h1>
        </AdminScreen>
    );
}