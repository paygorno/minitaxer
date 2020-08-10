import React from 'react';
import { Link } from 'react-router-dom';

const ShortenedAction = ({action}) => {
    let description, type;
    switch (action.type) {
        case 'income':
            description = `${Number(action.amount).toFixed(2)} ${action.currencyCode} credited to the account`;
            type = 'Income';
            break;
        case 'exchange':
            description = `${Number(action.amount).toFixed(2)} ${action.currencyCode} converted to ${Number(action.amountInUah).toFixed(2)} UAH with rate ${Number(action.rate).toFixed(2)}`;
            type = 'Exchange';
            break;
        case 'forceExchange':
            description = `${Number(action.amount).toFixed(2)} ${action.currencyCode} converted to ${Number(action.amountInUah).toFixed(2)} UAH with rate ${Number(action.rate).toFixed(2)}`;
            type = 'Force exchange';
            break;
    }
    return (
        <div>
            <div>{type}</div>
            <div>{Number(action.amount).toFixed(2)}</div>
            <div>{action.currencyCode}</div>
            <div>{description}</div>
            <div>{action.date}</div>
        </div>
    );
}

export default ShortenedAction;