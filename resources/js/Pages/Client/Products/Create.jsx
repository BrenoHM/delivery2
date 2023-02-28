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
        additions: [],
        variations: []
    });

    useEffect(() => {
        loadCategories();
    }, []);

    const loadCategories = async() => {
        axios.get(route('category.index', {dropdown:true})).then(result => {
            setCategories(result.data);
        });
    }

    const handleSubmit = (e) => {
        e.preventDefault();
        post(route('products.store'));
    }

    const onChangeField = (field, value, actionFunction = null, index = null) => {
        if(field == 'additions'){
            let additions = data[field];
            if( value.checked ) {
                additions.push(value.value)
                setData(field, additions);
            }else{
                //retira
                additions = additions.filter(addition => addition != value.value);
                setData(field, additions);
            }
        }else if(field == 'variations'){
            let variations = data[field];
            if( actionFunction == 'insert' ){
                
                variations.push({
                    variation_option_id: value.variation_option_id,
                    price: value.price
                })
                
            }else if( actionFunction == 'delete' ){
                variations = variations.splice(index, 1);
            }
            setData(field, variations);
            
        }else{
            setData(field, value);
        }
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
                    action={props.action}
                    props={props}
                />            
        </ClientScreen>
    )
}