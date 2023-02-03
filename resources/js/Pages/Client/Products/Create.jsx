import ClientScreen from '@/Layouts/ClientScreen';
import Form from '@/Pages/Client/Products/Form';
import { Head, useForm } from '@inertiajs/react';
import { useEffect, useState } from 'react';

export default function Create(props) {

    const [categories, setCategories] = useState([]);

    const { data, setData, post, processing, errors } = useForm({
        name: '',
        description: '',
        category_id: '',
        photo: '',
        price: '',
    });

    useEffect(() => {
        loadCategories();
    }, []);

    const loadCategories = async() => {
        axios.get(route('client.category', {dropdown:true})).then(result => {
            setCategories(result.data);
        });
    }

    const handleSubmit = (e) => {
        e.preventDefault();
        post(route('products.store'));
    }

    const onChangeField = (field, value) => {
        setData(field, value);
    }

    return (
        <ClientScreen {...props}>
            <Head title="Cadastrar Produto" />
                <h2 className='mb-5'>ADICIONAR PRODUTO</h2>
                <Form
                    categories={categories}
                    submit={handleSubmit}
                    onChangeField={onChangeField}
                    errors={errors}
                    processing={processing}
                    props={props}
                />            
        </ClientScreen>
    )
}