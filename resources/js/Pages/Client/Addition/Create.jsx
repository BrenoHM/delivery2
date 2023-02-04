import ClientScreen from '@/Layouts/ClientScreen';
import Form from '@/Pages/Client/Addition/Form';
import { Head, useForm } from '@inertiajs/react';

export default function Create(props) {

    const { data, setData, post, processing, errors } = useForm({
        addition: '',
        price: '',
        user_id: props.auth.user.id
    });

    const handleSubmit = (e) => {
        e.preventDefault();
        post(route('addition.store'));
    }

    const onChangeField = (field, value) => {
        setData(field, value);
    }

    return (
        <ClientScreen {...props}>
            <Head title="Cadastrar Acréscimo" />
                <h2 className='mb-5'>ADICIONAR ACRÉSCIMO</h2>
                <Form
                    submit={handleSubmit}
                    onChangeField={onChangeField}
                    errors={errors}
                    processing={processing}
                    props={props}
                />
        </ClientScreen>
    )
}