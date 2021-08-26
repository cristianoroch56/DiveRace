import React from "react";
import Header from "../Header";
import Footer from "../Footer";
import { useSelector } from "react-redux";
import { useHistory } from "react-router-dom";

const OrderSummary = () => {
  const isAuthenticated = useSelector((state) => state.user.isAuthenticated);

  let history = useHistory();

  React.useEffect(() => {}, []);

  return (
    <React.Fragment>
      <Header></Header>
      <section className="order-success-summary-wrapper mt-50 mb-50">
        <div className="container">
          <div className="row">
            <div className="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center">
              <img
                src={process.env.PUBLIC_URL + "/assets/order-fail.png"}
                alt="Order Fail"
                className="img-fluid order-fail-icon"
              />
            </div>
          </div>
        </div>
      </section>
      <Footer></Footer>
    </React.Fragment>
  );
};

export default OrderSummary;
