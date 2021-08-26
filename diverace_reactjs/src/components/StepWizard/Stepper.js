import React from 'react';

const stepTitle = ["Selection of Vessel", "Selection of Trip", "Selection of Cabins", "Selection of Courses", "Confirm & Payment"]

const Stepper = (props) => {

    return ( 
        <React.Fragment>
            <ul className="pr-5 nav nav-justified container main-step-section step-anchor">
                {
                    stepTitle.map((title, index)=> {
                        return (
                            
                                <li key={Math.random()} className={`nav-item avoid-clicks ${props.step === index+1 ? "active" : (props.step > index+1 ? "active" : "")}`}>
                                   
                                    <a href={`#step-${index+1}`}>
                                    
                                    <div>{`Step ${index+1}:`}</div>
                                    <small>{title}</small>
                                   
                                    </a>
                                </li>
                        )
                    })
                }
            </ul>
        </React.Fragment>
     );
}
 
export default Stepper;