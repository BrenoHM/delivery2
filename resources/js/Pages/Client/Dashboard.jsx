import ClientScreen from '@/Layouts/ClientScreen';
import { Head } from '@inertiajs/react';

export default function Dashboard(props) {
    return (
        <ClientScreen {...props}>
            <Head title="Dashboard Cliente" />

            <h1>Dashboard do cliente</h1>
        </ClientScreen>
    );
}