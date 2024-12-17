       
    const form = document.querySelector('#form1');
    const C_Name = document.querySelector('#CourseName');
    const F_Of_C = document.querySelector('#FeesOfCourse');
    const Duration = document.querySelector('#Duration');
    //const Password = document.querySelector('.Password');
  
    form.addEventListener('submit',(event)=>{
      event.preventDefault();

      checkInputs();
      
    });
  
    const checkInputs = ()=>{
      const courseValue = C_Name.value;
      const feescourselValue = F_Of_C.value;
      const durationValue = Duration.value;
      //const passwordValue = Password.value;
  
        const validStr1 = /^[a-zA-Z._]{3,20}$/;
        const validFees = /^+?[0-9]{2,10}$/;
        const validDuration = /^[0-9][a-zA-Z]$/;
       // const validEmail = /^[a-zA-Z0-9._%]+@[a-zA-Z0-9.-]{2,}$/;
     //   const validPhone = /^\+?[0-9]{7,15}$/;
    //    const validPassword = /^[a-zA-Z0-9!@#$%^&*()_+=\-{}[\],.?/|\\]{4,20}$/;
  
        if(!validStr1.test(courseValue)){
          document.getElementById("CourseName").style.border = "2px solid red";
          document.getElementById("ppp1").style.display = "inherit";
        }
        if(!validFees.test(feescourselValue)){
          document.getElementById("FeesOfCourse").style.border = "2px solid red";
          document.getElementById("ppp1").style.display = "inherit";
        }
        if(!validDuration.test(durationValue)){
          document.getElementById("Duration").style.border = "2px solid red";
          document.getElementById("ppp2").style.display = "inherit";
        }

        // if(!validPassword.test(passwordValue)){
        //   document.getElementsByClassName("Password").style.border = "2px solid red";
        //   //document.getElementById("ppp3").style.display = "inherit";
        // }

      }