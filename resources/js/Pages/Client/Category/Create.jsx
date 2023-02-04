import ClientScreen from '@/Layouts/ClientScreen';
import Form from '@/Pages/Client/Category/Form';
import { Head, useForm } from '@inertiajs/react';

export default function Create(props) {

    const { data, setData, post, processing, errors } = useForm({
        categorie: '',
        user_id: props.auth.user.id
    });

    const handleSubmit = (e) => {
        e.preventDefault();
        post(route('category.store'));
    }

    const onChangeField = (field, value) => {
        setData(field, value);
    }

    return (
        <ClientScreen {...props}>
            <Head title="Cadastrar Categoria" />
                <h2 className='mb-5'>ADICIONAR CATEGORIA</h2>
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