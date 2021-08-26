import React from "react";


const Footer = () => {
  return (
    <React.Fragment>

    <div className="container-fluid footer-section-top bg-gray">
	    <div className="container">
	    	<div className="row">
	      		<div className="col-sm-12 col-md-12 text-center faq-section margin-top-60">
			    	<span className="fw-bold">More questions?</span> Check out our extensive FAQs page <a href="https://diverace.chillybin.biz/docs/" className="faq_link" target="_blank" rel="noopener">here</a>			    	
				</div>
			</div>
		</div>	
	</div>	

    <div className="container-fluid footer-copy-right-section">
	    <div className="container">
		    <div className="row">
		      	
		        	<div className="col- col-sm-8 col-md-8 copyright-area">
		        		<span className="text-clr">© 2020 DiveRACE. All rights reserved.</span>
		        		<span className="text-clr website-made-by">Website by
		        		<a style={{backgroundImage: `url(${process.env.PUBLIC_URL + "/assets/cb.svg"})`}}
		        		className="chillybin" target="_blank" href="https://www.chillybin.com.sg/wordpress-development/" rel="nofollow"><span className="screen-reader-text">Singapore WordPress Developer</span></a>
		        		</span>
		        	</div>
		        	<div className="col- col-sm-4 col-md-4 website-area">
		        		<img
                          src={process.env.PUBLIC_URL + "/assets/visa_pay.svg"}
                          className="payment-icon"
                          alt="visa payment"
                        />
                        <img
                          src={process.env.PUBLIC_URL + "/assets/master_pay.svg"}
                          className="payment-icon"
                          alt="visa payment"
                        />
                        <img
                          src={process.env.PUBLIC_URL + "/assets/am_pay.svg"}
                          className="payment-icon"
                          alt="visa payment"
                        />
                        <img
                          src={process.env.PUBLIC_URL + "/assets/g_pay.svg"}
                          className="payment-icon"
                          alt="visa payment"
                        />
		        	</div>



		      	
		    </div>
	    </div>
	</div>
    </React.Fragment>
  );
};

export default Footer;
