# MySensors Php API

This class implements methods to directly send messages (and get answers) to a [MySensors](http://www.mysensors.org) gateway.


### Main Files
- **example.php** : Basic usage example
- **form.php** : Send (or visually convert) messages to your Gateway
- **src/mysensors_bin.php** : an excecutable script to send/receive message from the command line
- **src/mysensors.class.php** : The main class
- **src/PhpSerial.php** : Serial handling [class by Rémy Sanchez](https://github.com/Xowap/PHP-Serial/)


### Sender / Converter Form
*form.php* allow you compose messages using convenient dynamic drowndown menu. resulting message is then shown in the message box.
You can also do the reverse action : type any text in the message box, and it will dynamically decode it.
Finally  you can also send the message to the gateway.

![Form screenshot](/images/form.png)


### Command Line Script
Here is the *mysensors_bin.php* help.

	soif@server:~# ./mysensors_bin.php -h
	Usage: 
		mysensors_bin.php [-p port] [-a] [-g] [-v] [-h] ADDRESS COMMAND
	
		-p port 	: set the gateway TCP port if not 5003
		-a		: send an ACK
		-g 		: get answer
		-v 		: verbose (show message sent to gateway)
		-h 		: show this help
	
		ADDRESS	: (required) Gateway IP address or SerialPort to use

		COMMAND		: (required) is one of the following commands:
			- presentation NODE CHILD TYPE
			- set NODE CHILD TYPE PAYLOAD
			- req NODE CHILD TYPE
			- internal NODE CHILD TYPE

			Using the following parameters :
			- NODE 		: Node ID
			- CHILD		: Child Sensor ID
			- TYPE		: Sub Type
			- PAYLOAD	: value to send
	Examples:
		mysensors_bin.php -g 192.168.0.240 internal 0 0 I_VERSION
		mysensors_bin.php -p 5002 192.168.0.240 presentation 5 1 V_TEMP
		mysensors_bin.php -a 192.168.0.240 set 12 0 V_STATUS 0	
		mysensors_bin.php COM1 set 12 0 V_STATUS 1	


## Disclaimer
- Currently only the Ethernet Gateway is supposed to work. **The Serial Gateway if just a draft**, for somebody willing to test it and implementing correctly (Push  your PR!).
- Missing some detailed documentation about the MySensors messages protocol, some features may not work as expected (ie: the *req* command was not tested). So I won't be responsible if you'd burn your house or kill a cat by using this software! ;-)


## Contributions
Contributions are welcome ! Please fork this repo at GitHub and submit your PR to the **develop** branch.


## Licence

This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.

