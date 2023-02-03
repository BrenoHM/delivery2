import ClientScreen from '@/Layouts/ClientScreen';
import Form from '@/Pages/Client/Products/Form';
import { Head, useForm } from '@inertiajs/react';
import { useEffect, useState } from 'react';

export default function Edit(props) {

    const [categories, setCategories] = useState([]);

    const { data, setData, post, processing, errors } = useForm({
        name: props.product.name,
        description: props.product.description,
        category_id: props.product.category_id,
        photo: '',
        price: props.product.price,
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
        //post(route('products.store'));
        post(route('products.update', props.product.id));
    }

    const onChangeField = (field, value) => {
        setData(field, value);
    }

    return (
        <ClientScreen {...props}>
            <Head title="Cadastrar Produto" />
                <h2 className='mb-5'>EDITAR PRODUTO</h2>
                <Form
                    categories={categories}
                    submit={handleSubmit}
                    onChangeField={onChangeField}
                    errors={errors}
                    processing={processing}
                    data={data}
                    action={props.action}
                    props={props}
                />            
        </ClientScreen>
    )
}