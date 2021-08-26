import React from "react";
import { useDispatch, useSelector } from "react-redux";
import { fetchBookedTripSummary } from "../../store/actions/StepWizardAction";


const OrderBookedSummary = (props) => {

  const redux = useSelector((state) => state.StepWizardReducer);
  const summaryData = useSelector((state) => state.StepWizardReducer.order_booked_summary);

  const [propsData, setPropsData] = React.useState(props);

  const dispatch = useDispatch();
  
  React.useEffect(() => {
   
    const ORDER_ID = Number(propsData.order_id);
    const USER_ID = Number(propsData.user_id);
    dispatch(fetchBookedTripSummary(ORDER_ID, USER_ID));
  }, []);

  return (
    <React.Fragment>
      <div className="body-right col-md-5 col-summarydetails-section">
        <div className="area-right cust-box-shadow summary-details-wrapper">
          <div className="box-heading">
            <h5 style={{ fontWeight: 700 }}>Summary</h5>
          </div>

          <hr className="hr-line"></hr>

          {/* Step-1 */}
          <div className="box col-md-12">
            <div className="cls cls-num col-2 col-md-2">
              <span className="num" style={{backgroundImage: `url(${process.env.PUBLIC_URL + "/assets/circle-bg.png"})`}}>
                1
              </span>
            </div>
            <div className="text col-8 col-md-8">
              <h6 className="summary-text-title">Selection of Vessel</h6>
              <span className="summary-text-info">
                {summaryData.vessel_title
                  ? summaryData.vessel_title    
                  : "No vessel"}
              </span>
            </div>
            <div className="box-check col-2 col-md-2">
              <i className="fa fa-check" aria-hidden="true"></i>
            </div>
          </div>

          {/* Step-2 */}
          <div className="box box-color col-md-12">
            <div className="cls cls-num col-2 col-md-2">
              <span className="num" style={{backgroundImage: `url(${process.env.PUBLIC_URL + "/assets/circle-bg.png"})`}}>
                2
              </span>
            </div>
            <div className="text col-8 col-md-8">
              <h6 className="summary-text-title">Selection of Trip</h6>

              <span className="summary-text-info">
                {`${
                  summaryData.country_title
                    ? summaryData.country_title
                    : "No country "
                } , ${
                  summaryData.itinery_title
                    ? summaryData.itinery_title
                    : "No Itinerary area "
                }`}
                <br />
                {summaryData.itternary_date_summary
                  ? summaryData.itternary_date_summary
                  : "No date "}
              </span>
            </div>
            <div className="box-check col-2 col-md-2">
              <i className="fa fa-check" aria-hidden="true"></i>
            </div>
          </div>

          {/* Step-3 */}
          <div className="box box-area col-md-12">
            <div className="cls cls-num col-2 col-md-2">
              <span className="num" style={{backgroundImage: `url(${process.env.PUBLIC_URL + "/assets/circle-bg.png"})`}}>
                3
              </span>
            </div>
            <div className="text col-8 col-md-8">
              <h6 className="summary-text-title">Selection of Cabins</h6>
              <span className="summary-text-info">
            
              {summaryData.cabin_data !== undefined ? 
                    <div>
                    
                        {( summaryData.cabin_data.length > 0 )
                        ? summaryData.cabin_data.map((cabin_obj, i) => {
                            return (
                                <React.Fragment key={i}>
                                {`${cabin_obj.cabin_title}(${
                                    cabin_obj.cabin_type === "solo"
                                    ? "Solo"
                                    : cabin_obj.selected_seat === "both"
                                    ? "2pax"
                                    : "Solo"
                                })`}
                                <br />
                                
                                </React.Fragment>
                            );
                            })
                        : "No cabin"}

                        </div>  : ("") }
              
         
              </span>
            </div>
            <div className="box-check col-2 col-md-2">
              <i className="fa fa-check" aria-hidden="true"></i>
            </div>
          </div>

          {/* Step-4 */}
          <div className="box box-color col-md-12">
            <div className="cls cls-num col-2 col-md-2">
              <span className="num" style={{backgroundImage: `url(${process.env.PUBLIC_URL + "/assets/circle-bg.png"})`}}>
                4
              </span>
            </div>
            <div className="text col-8 col-md-8">
              <h6 className="summary-text-title">Selection of add ons</h6>
              <span className="summary-text-info">

                 {summaryData.cabin_data !== undefined ?  
                 <div>
                 { (summaryData.courses_data && summaryData.courses_data.length > 0 )
                   ? summaryData.courses_data.map((course_pbj, i) => {
                       return (
                         <React.Fragment key={i}>
                           {`${course_pbj.courses_title} x ${course_pbj.courses_person}`}
                           <br />
                         </React.Fragment>
                       );
                     })
                   : "No course selected"}
                 <br />
                 </div>
                 : ""}
                
                {summaryData.cabin_data !== undefined ?  
                <div>
                {summaryData.rental_equipment_data && summaryData.rental_equipment_data.length > 0
                  ? summaryData.rental_equipment_data.map((rental_pbj, i) => {
                      return (
                        <React.Fragment key={i}>
                          {`${rental_pbj.rental_equipment_title} x ${rental_pbj.rental_equipment_person}`}
                          <br />
                        </React.Fragment>
                      );
                    })
                  : "No Rental equipment selected"}
                <br />{" "}
                </div> : ""}

              </span>
            </div>
            <div className="box-check col-2 col-md-2">
              <i className="fa fa-check" aria-hidden="true"></i>
            </div>
          </div>
          {/* {redux.updated_final_amount > 0.00 &&  */}
              <div className="box box-area col-md-12" >
              <div className="text col-3 col-md-7">
                <h6 className="amount-title">Total Cost:</h6>
              </div>
              <div className="col-9 col-md-5">
              
                <h4 className="font-w-500 wb-all" style={{ color: "#3396d8" }}> S$ {redux.update_addons_amount > 0.00 ? redux.update_addons_amount : 0}</h4>

              </div>
            </div>
            {/* Discount */}
            <div style={{display:`${(redux.update_addons_amount > 0 && summaryData && summaryData.user_credit && summaryData.user_credit > 0)  ? " " : "none"}`}}>
              <div className="box box-area col-md-12 rm-p-tb" >
                <div className="text col-3 col-md-7">
                  <h6 className="amount-title">Discount:</h6>
                 
                </div>
                <div className="col-9 col-md-5" >
                  {summaryData && 
                  <p style={{display:`${Number(redux.user_credit) && redux.user_credit !== undefined && (redux.user_credit) > 0 ? " " : "none"}`}}>-Credit {`$S ${redux.user_credit}`}</p>
                  }
                  
                </div>
              </div>
               {/* Discount */}
              <div className="box box-area col-md-12 rm-p-tb">
                <div className="text col-3 col-md-7">
                        <h6 className="amount-title">Total Cost::</h6>
                      
                </div>
                <div className="col-9 col-md-5" >
                  <h4 className="font-w-500 wb-all" style={{ color: "#3396d8"}}> S$ {redux.updated_final_amount > 0.00 ? redux.updated_final_amount : 0}</h4>
                </div>
              </div>
              </div>
            {/* Discount */}

        
          
        </div>
      </div>
    </React.Fragment>
  );
};

export default OrderBookedSummary;
