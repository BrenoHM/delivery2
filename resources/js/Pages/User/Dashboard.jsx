import UserScreen from '@/Layouts/UserScreen';
import { Head } from '@inertiajs/react';

export default function Dashboard(props) {
    return (
        <UserScreen {...props}>
            <Head title="Dashboard Usuário" />

            <h1>Dashboard do usuário</h1>
        </UserScreen>
    );
}