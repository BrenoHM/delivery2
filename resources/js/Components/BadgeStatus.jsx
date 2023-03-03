
import { statusOrder } from '@/helper';

export default function BadgeStatus({status}) {

    if(status == 1){
        return <span className="bg-yellow-100 text-yellow-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-yellow-900 dark:text-yellow-300">{statusOrder[status]}</span>
    }

    if(status == 2){
        return <span className="bg-gray-100 text-gray-800 text-sm font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300">{statusOrder[status]}</span>
    }

    if(status == 3){
        return <span className="bg-purple-100 text-purple-800 text-sm font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-purple-900 dark:text-purple-300">{statusOrder[status]}</span>
    }

    if(status == 4){
        return <span className="bg-green-100 text-green-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300">{statusOrder[status]}</span>
    }

    if(status == 5){
        return <span className="bg-red-100 text-red-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-red-900 dark:text-red-300">{statusOrder[status]}</span>
    }

}

