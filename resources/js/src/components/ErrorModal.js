import React from 'react';
import { mapValues } from 'lodash';

const ErrorModal = ({error, handle}) => {
    let explanation = [];
    if (!Array.isArray(error.errors)) {
        for (const key in error.errors) {
            if (error.errors.hasOwnProperty(key)) {
                const element = error.errors[key];
                explanation.push(element)
            }
        }
    } else explanation = error.errors;
    return (
        <div>
            <div>
                {
                    error.message
                }
            </div>
            <div>
                {
                    explanation.map((o, index) => <div key={index}>{o}</div>)
                }
            </div>
            <div>
                <button onClick={handle}>Close</button>
            </div>
        </div>
    );
}

export default ErrorModal;