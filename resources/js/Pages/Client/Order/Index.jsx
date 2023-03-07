import ClientScreen from '@/Layouts/ClientScreen';
import { Head, Link, usePage } from '@inertiajs/react';
import DataTable from 'react-data-table-component';
import { BiTrash, BiPencil } from "react-icons/bi";
import { useState, useEffect, useMemo } from 'react';
import { modalExcluded, CustomLoader } from '@/helper';
import FilterComponent from '@/Components/FilterComponent';
import OrderDetail from '@/Components/OrderDetail';
import BadgeStatus from '@/Components/BadgeStatus';

export default function Index(props) {

    const {flash} = usePage().props
    const {primaryColor, secondaryColor} = props.auth.user.tenant;
    const [data, setData] = useState([]);
	const [loading, setLoading] = useState(false);
    const [filterText, setFilterText] = useState('');
    const [opened, setOpened] = useState(false);
    const [call, setCall] = useState('');
    const [firstFetchProducts, setFirstFetchProducts] = useState(false);
    const [rowsExpandables, setRowsExpandables] = useState([]);

    const item = data.map((d, i) => {
        return { ...d, index: i };
    });

    const filteredItems = filterText ? item.filter(item => item.id == filterText) : item;

	const fetchOrders = async () => {

        setLoading(true);
        const response = await axios.get(route('order.index', {json: true}));
		setData(response.data);
		setLoading(false);
	};

    const subHeaderComponentMemo = useMemo(() => {
		const handleClear = () => {
			if (filterText) {
				setFilterText('');
			}
		};

		return (
			<FilterComponent onFilter={e => setFilterText(e.target.value)} onClear={handleClear} filterText={filterText} />
		);
	}, [filterText]);

    useEffect(() => {

        if( !firstFetchProducts ) {
            fetchOrders();
            setFirstFetchProducts(true);
        }

        //calls per minute
        if (!opened) {
            setCall(setInterval(function(){
                fetchOrders()
            }, 60000));
        } else {
            setCall(clearInterval(call));
        }

	}, [opened]);

    const columns = [
        {
            name: 'Pedido N°',
            selector: row => '#'+row.id,
        },
        {
            name: 'Nome',
            selector: row => row.name,
        },
        {
            name: 'Data',
            selector: row => row.created_at,
        },
        {
            name: 'Total',
            selector: row => row.total.toLocaleString('pt-br', {style: 'currency', currency: 'BRL'}),
        },
        {
            name: 'Status',
            selector: row => <BadgeStatus status={row.status_order_id} />,
        },
    ];

    const changeStatus = async (id, i, value) => {
        try {

            const result = await axios.patch(route('order.changeStatus', { order: id, status_order_id: value }));
            if( result.data.success ) {
                filteredItems[i].status_order_id = value;
                setData(filteredItems)
            }
            
        } catch (error) {
            alert('Erro ao alterar status.');
        }
        
    }

    const handleExpandable = (toogled, row) => {
        var rows = rowsExpandables;
        if( toogled ){
            setOpened(toogled);
            rows.push(row);
            setRowsExpandables(rows);
        }else{
            // Only perform calls automatically when all lines are closed
            rows = rows.filter(r => r.index !== row.index)
            if( rows.length == 0 ) {
                setOpened(toogled);
            }
            setRowsExpandables(rows);
        }
        
    }

    return (
        <ClientScreen {...props}>
            <Head title="Listagem de Produtos" />

            <DataTable
                title="Pedidos"
                noDataComponent="Não há pedidos para exibir"
                columns={columns}
                data={filteredItems}
                progressPending={loading}
                striped
                subHeader
                subHeaderComponent={subHeaderComponentMemo}
                progressComponent={<CustomLoader />}
                expandableRows
                onRowExpandToggled={handleExpandable}
                expandableRowsComponent={({data}) => <OrderDetail data={data} index={data.index} handleChangeOrderStatus={changeStatus} />}
            />            
        </ClientScreen>
    );
}