import React, { useEffect } from "react";
import StripeCheckout from "react-stripe-checkout";
import { useDispatch, useSelector } from "react-redux";
import { useHistory} from "react-router-dom";
import {
  setStep,
  showMessage,
  updateForm,
  bookedTrip,
  setTransactionDetails
} from "../store/actions/StepWizardAction";

const Checkout = (props) => {

  const redux_store = useSelector((state) => state.StepWizardReducer);
  const payment_amount = useSelector((state) => state.StepWizardReducer.final_payble_amount);
  const partial_amount = useSelector((state) => state.StepWizardReducer.partial_amount);

  const [stripeAmount, setStripeAmount] = React.useState(payment_amount);

  

  const dispatch = useDispatch();
  let history = useHistory();

  const onToken = (token, addresses) => {

    if (Object.entries(token).length !== 0) {

      dispatch(updateForm('showLoader',true));

      let transaction_id = "transaction_id";
      const card_id = "card_id";
      const client_ip = "client_ip";
      const created = "created";
      const email = "email";
      
      const transactionDetails = {
        [transaction_id]: token.id,
        [card_id]: token.card.id,
        [client_ip]: token.client_ip,
        [created]: token.created,
        [email]: token.email,
      };
      const request_data = {
        ...redux_store,
        transaction_data:transactionDetails
      }
      dispatch(setTransactionDetails(transactionDetails));
      setTimeout(() => {
        dispatch(bookedTrip(request_data,history));
      }, 2000);
      // dispatch(bookedTrip());

      /*       
      dispatch(setStep(1))
      dispatch(showMessage('block')); */
      // history.push("/");
    }
  };

  const paymentAmount = () => {
    if (Number(payment_amount) == 0) {
      
      dispatch(updateForm('showLoader',true));

      let transaction_id = "transaction_id";
      const card_id = "card_id";
      const client_ip = "client_ip";
      const created = "created";
      const email = "email";
      
      const transactionDetails = {
        [transaction_id]: 'NA',
        [card_id]: 'NA',
        [client_ip]: 'NA',
        [created]: 'NA',
        [email]: 'NA',
      };
      const request_data = {
        ...redux_store,
        final_payble_amount:redux_store.final_total_amount,
        transaction_data:transactionDetails
      }
      dispatch(setTransactionDetails(transactionDetails));
      setTimeout(() => {
        dispatch(bookedTrip(request_data,history));
      }, 2000);
      
    }
  }

  useEffect(() => {

    let stripe_show_amount = payment_amount;
    if(partial_amount != 0 || partial_amount != null) {
      stripe_show_amount =  Number(partial_amount);
    }
    setStripeAmount(stripe_show_amount);
  }, []);


  return (
    <React.Fragment>
      {Number(payment_amount) == 0 ?
          <div>
            <button className="btn btn-primary btn-lg" onClick={paymentAmount}>Proceed To Payment</button>
          </div> : 
          <StripeCheckout
          stripeKey="pk_test_XuxReVpfIJThZQmYTXVwCcXV"
          //amount={Number(stripeAmount * 100)}
          currency="SGD"
          token={onToken}
        >
          {/* Custom Button */}
          <button className="btn btn-primary btn-lg" disabled={props.disabled} >Proceed To Payment</button>
          {/* Custom Button */}
        </StripeCheckout>
    }
    </React.Fragment>
    
  );
};

export default Checkout;
