import React from 'react';

export const options = {
    systemCurrency: [
        { value: 'SGD', label: <div><img width="22px" className="mr-2" src={process.env.PUBLIC_URL + "/assets/singapore_flag.png"} /> {'   '} {`SGD`} </div>, icon: 'S$' },
        { value: 'USD', label: <div><img width="22px" className="mr-2" src={process.env.PUBLIC_URL + "/assets/usa_flag.png"} /> {' '} {`UGD`} </div>, icon: '$' },
        { value: 'THB', label: <div><img width="22px" className="mr-2" src={process.env.PUBLIC_URL + "/assets/thailand_flag.png"} /> {' '} {`THB`} </div>, icon: 'B$' }
    ]
}

export const defaults = {
    defCurrency: options.systemCurrency[0]
}  