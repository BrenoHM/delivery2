import { paymentMethod } from '@/helper';
import { RiWhatsappLine } from "react-icons/ri";

export default function OrderDetail({ data, index, handleChangeOrderStatus }) {

    return (
        <>
            { data.additional_information && (
                <div className="flex items-center bg-blue-500 text-white text-sm font-bold px-4 py-3" role="alert">
                    <svg className="fill-current w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M12.432 0c1.34 0 2.01.912 2.01 1.957 0 1.305-1.164 2.512-2.679 2.512-1.269 0-2.009-.75-1.974-1.99C9.789 1.436 10.67 0 12.432 0zM8.309 20c-1.058 0-1.833-.652-1.093-3.524l1.214-5.092c.211-.814.246-1.141 0-1.141-.317 0-1.689.562-2.502 1.117l-.528-.88c2.572-2.186 5.531-3.467 6.801-3.467 1.057 0 1.233 1.273.705 3.23l-1.391 5.352c-.246.945-.141 1.271.106 1.271.317 0 1.357-.392 2.379-1.207l.6.814C12.098 19.02 9.365 20 8.309 20z"/></svg>
                    <p>Informação adicional: { data.additional_information }</p>
                </div>
            ) }
            <div className="grid grid-cols-[40%,60%] p-5">
                <div className="w-25">
                    <h3><strong>Endereço de entrega {index}</strong></h3>
                    <p className='flex'>
                        <span className='mr-1'>{ data.name }</span>- 
                        <a target='_blank' title='Enviar Whatsapp' href={`https://wa.me/+55${data.phone.replace(/[() -]/g, '')}?text=Olá%20gostaria%20de%20falar%20sobre%20seu%20pedido.`} className='flex items-center'>
                            <RiWhatsappLine className='mr-1 ml-1 text-green-600' /> { data.phone }
                        </a>
                    </p>
                    { data.delivery_method == 'shipping' ? (
                        <>
                            {data.street}, {data.number}{data.complement ? ', ' + data.complement : ''}, {data.neighborhood}<br />
                            {data.zip_code} - {data.city}/{data.state}
                        </>
                    )
                    :
                    'Retirar no local' }
                </div>
                <div className="w-75">
                    <table className="w-full text-sm text-left dark:text-gray-400">
                        <thead className="text-xs uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <td className='px-6 py-3'>Quantidade</td>
                                <td className='px-6 py-3'>Produto</td>
                                <td className='px-6 py-3'>Variação</td>
                                <td className='px-6 py-3'>Acréscimo</td>
                                <td className='px-6 py-3'>Subtotal</td>
                            </tr>
                        </thead>
                        <tbody>
                            { data.items && data.items.map(item => (
                                <tr key={'item-'+item.id}>
                                    <td className='px-6 py-3'>x{ item.quantity }</td>
                                    <td className='px-6 py-3'>{ item.product.name }</td>
                                    <td className='px-6 py-3'>{ item.variation?.product_variation.option.option }</td>
                                    <td className='px-6 py-3'>
                                        { item.additions && item.additions.map(addition => (
                                            <div key={addition.id}>
                                                +{addition.addition.addition}<br />
                                            </div>
                                        )) }
                                    </td>
                                    <td className='px-6 py-3'>{ item.total.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}) }</td>
                                </tr>
                            )) }
                            <tr>
                                <td className="text-right px-6 py-3" colSpan={4}>Taxa de frete</td>
                                <td className='px-6 py-3'>{ data.freight_total.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}) }</td>
                            </tr>
                            <tr>
                                <td className="text-right px-6 py-3" colSpan={4}>Total do pedido</td>
                                <td className='px-6 py-3'><strong>{ data.total.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}) }</strong></td>
                            </tr>
                            <tr>
                                <td className="text-right px-6 py-3" colSpan={4}>Método de pagamento</td>
                                <td className='px-6 py-3'>{ paymentMethod[data.payment_method] }</td>
                            </tr>
                            <tr>
                                <td className="text-right px-6 py-3" colSpan={4}>Mudar Status</td>
                                <td className='px-6 py-3'>
                                    <select onChange={(e) => handleChangeOrderStatus(data.id, index, e.target.value)} value={data.status_order_id}>
                                        <option value="1">Aberto</option>
                                        <option value="2">Em preparação</option>
                                        <option value="3">Em transporte</option>
                                        <option value="4">Concluído</option>
                                        <option value="5">Cancelado</option>
                                    </select>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    {/* <pre>{JSON.stringify(data, null, 2)}</pre> */}
                </div>
            </div>
            
        </>
    );
}