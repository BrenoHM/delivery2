import PrimaryButton from '@/Components/PrimaryButton';
import { Link } from '@inertiajs/react';
import { useState, useEffect } from 'react';

export default function Form({categories, submit, onChangeField, errors, processing, data, action, props}) {

    const [variations, setVariations] = useState(props.variations ?? []);
    const [variation_option_id, setVariationOptionId] = useState('');
    const [price, setPrice] = useState('');
    const {primaryColor, secondaryColor} = props.auth.user.tenant;

    const deschecked = i => {
        props.additions[i].checked = !props.additions[i].checked;
    }

    const addVariation = (actionFunction, action, index) => {
        if ( (variation_option_id && price) || actionFunction ) {

            var aux = variations;
            if( actionFunction == 'insert' ){
                if(action == 'Salvar'){
                    aux.push({
                        variation_option_id,
                        price
                    });
                }
            }else if(actionFunction == 'delete') {
                aux.splice(index, 1);
            }
            
            setVariations(aux);

            if(action == 'Editar') {
                onChangeField('variations', {
                    id: null,
                    product_id: props.product.id,
                    variation_option_id,
                    price
                }, actionFunction, index);

            }else if(action == 'Salvar'){
                onChangeField('variations', {
                    variation_option_id,
                    price
                }, actionFunction, index);
            }

            setVariationOptionId('');
            setPrice('');
        }
    }
    
    return(
        <form className="w-full max-w-2xl" onSubmit={submit} encType="multipart/form-data">
            <div className="flex flex-wrap -mx-3 mb-6">
                <div className="w-full px-3 mb-6 md:mb-0">
                    <label className="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" htmlFor="name">
                        Nome
                    </label>
                    <input
                        className={`appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white ${errors.name && 'border-red-500'}`}
                        id="name"
                        type="text"
                        placeholder="Ex. X-TUDO"
                        onChange={e => onChangeField('name', e.target.value)}
                        value={data?.name}
                    />
                    {errors.name && <p className="text-red-500 text-xs italic">{errors.name}</p>}
                </div>
            </div>
            <div className="flex flex-wrap -mx-3 mb-6">
                <div className="w-full px-3 mb-6 md:mb-0">
                    <label className="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" htmlFor="description">
                        Descrição
                    </label>
                    <textarea
                        className={`appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white`}
                        id="description"
                        type="text"
                        placeholder="Ex. Delicioso Sanduiche"
                        onChange={e => onChangeField('description', e.target.value)}
                        value={data?.description}
                        rows={10}
                    />
                </div>
            </div>
            <div className="flex flex-wrap -mx-3 mb-6">
                <div className="w-full px-3 mb-6 md:mb-0">
                    <label className="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" htmlFor="category_id">
                        Categoria
                    </label>
                    <select
                        className={`block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 mb-3 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500 ${errors.category_id && 'border-red-500'}`}
                        id="category_id"
                        onChange={e => onChangeField('category_id', e.target.value)}
                        value={data?.category_id}>
                        <option value="">Selecione</option>
                        { categories.map(category => (
                            <option value={category.id} key={category.id}>{category.categorie}</option>
                        ))}
                    </select>
                    {errors.category_id && <p className="text-red-500 text-xs italic">{errors.category_id}</p>}
                </div>
            </div>
            <div className="flex flex-wrap -mx-3 mb-6">
                <div className="w-full md:w-1/2 px-3">
                    <label className="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" htmlFor="photo">
                        Foto
                    </label>
                    <input
                        className={`appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white ${errors.photo && 'border-red-500'}`}
                        id="photo"
                        type="file"
                        onChange={e => onChangeField('photo', e.target.files[0])}
                    />
                    {errors.photo && <p className="text-red-500 text-xs italic">{errors.photo}</p>}
                </div>
                <div className="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                    <label className="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" htmlFor="price">
                        Preço
                    </label>
                    <input
                        className={`appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white ${errors.price && 'border-red-500'}`}
                        id="price"
                        type="number"
                        onChange={e => onChangeField('price', e.target.value)}
                        value={data?.price}
                        min="1"
                        step=".01"
                    />
                    {errors.price && <p className="text-red-500 text-xs italic">{errors.price}</p>}
                </div>
            </div>
            <div className="flex flex-wrap -mx-3 mb-6">
                <div className="w-full px-3 mb-6 md:mb-0">
                    <label className="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">
                        Acréscimos
                    </label>
                    {props.additions && props.additions.map((addition, i) => (
                        <label className='block' key={addition.id}>
                            <input
                                className="mr-2"
                                type="checkbox"
                                onChange={e => {
                                    onChangeField('additions', e.target)
                                    deschecked(i)
                                }}
                                value={addition.id}
                                checked={addition.checked ?? false} />
                                {addition.addition}
                        </label>
                    ))}
                    
                </div>
            </div>
            <div className="flex flex-wrap -mx-3 mb-6">
                <div className="w-full px-3 mb-6 md:mb-0">
                    <label className="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">
                        Variações
                    </label>
                    <select
                        value={variation_option_id}
                        onChange={(e) => setVariationOptionId(e.target.value)}
                        className={`appearance-none w-1/4 bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 mb-3 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500`}>
                        <option value="">Selecione</option>
                        {props.variationOptions && props.variationOptions.map((option, i) => (
                            <optgroup key={i} label={option.variation}>
                                { option.options && option.options.map((o, j) => (
                                    <option key={j} value={o.id}>{o.option}</option>
                                )) }
                            </optgroup>
                        ))}
                    </select>
                    <input
                        type="number"
                        min={1}
                        step=".01"
                        value={price}
                        onChange={(e) => setPrice(e.target.value)}
                        className='appearance-none w-1/4 bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 mb-3 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500 mx-2' />
                    
                    <button
                        type='button' 
                        className='inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150'
                        title='Adicionar Variação'
                        onClick={() => addVariation('insert', action)}>
                            Adicionar
                    </button>
                </div>
            </div>

            <div className="flex flex-wrap -mx-3 mb-6">
                <div className="w-full px-3 mb-6 md:mb-0">
                    <label className="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">
                        Variações Adicionadas
                    </label>
                    <table className='w-full text-sm text-left text-gray-500 dark:text-gray-400'>
                        <thead className='text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400'>
                            <tr>
                                <td className='px-6 py-3'>Variação</td>
                                <td className='px-6 py-3'>Preço</td>
                                <td className='px-6 py-3'>Excluir </td>
                            </tr>
                        </thead>
                        <tbody>
                            { variations && variations.map((variation, i) => (
                                <tr key={i}>
                                    <td className='bg-white border-b dark:bg-gray-800 dark:border-gray-700 text-center'>{variation.variation_option_id}</td>
                                    <td className='bg-white border-b dark:bg-gray-800 dark:border-gray-700 text-center'>R$ {variation.price}</td>
                                    <td className='bg-white border-b dark:bg-gray-800 dark:border-gray-700 text-center'>
                                        <button
                                            onClick={() => addVariation('delete', action, i)}
                                            type='button'
                                            className='inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150'>
                                            X
                                        </button>
                                    </td>
                                </tr>
                            )) }
                        </tbody>
                    </table>
                </div>
            </div>

            <div className="text-center">
                <PrimaryButton className="mr-2" processing={processing} style={{background: primaryColor ? primaryColor : props.defaultPrimaryColor}}>{action ?? 'Salvar'}</PrimaryButton>
                <Link href={route('client.products')} className="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">Cancelar</Link>
            </div>
        </form>
    )
}