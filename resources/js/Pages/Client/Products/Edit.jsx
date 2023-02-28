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
        additions: props.idsAdditions,
        variations: props.variations
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
        //post(route('products.store'));
        post(route('products.update', props.product.id));
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
                    id: null,
                    product_id: props.product.id,
                    variation_option_id: value.variation_option_id,
                    price: value.price
                })
                
            }else if( actionFunction == 'delete' ){
                variations.splice(index, 1);
            }
            setData(field, variations);
            
        }else{
            setData(field, value);
        }
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