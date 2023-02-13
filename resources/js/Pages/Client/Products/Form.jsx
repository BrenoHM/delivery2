import PrimaryButton from '@/Components/PrimaryButton';
import { Link } from '@inertiajs/react';

export default function Form({categories, submit, onChangeField, errors, processing, data, action, props}) {
    const {primaryColor, secondaryColor} = props.auth.user.tenant;

    const deschecked = i => {
        props.additions[i].checked = !props.additions[i].checked;
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
                                checked={addition.checked} />
                                {addition.addition}
                        </label>
                    ))}
                    
                </div>
            </div>
            <div className="text-center">
                <PrimaryButton className="mr-2" processing={processing} style={{background: primaryColor ? primaryColor : props.defaultPrimaryColor}}>{action ?? 'Salvar'}</PrimaryButton>
                <Link href={route('client.products')} className="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">Cancelar</Link>
            </div>
        </form>
    )
}