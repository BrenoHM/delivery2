export default function OrderDetail({ data }) {
    return <pre>{JSON.stringify(data, null, 2)}</pre>;
}