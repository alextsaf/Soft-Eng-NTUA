import sys
import argparse
import requests
import insert
import os



#Parser Initialization
parser = argparse.ArgumentParser(description = 'CLI Interoperability API')

#Format Parser
formatparse = argparse.ArgumentParser(add_help=False)
formatparse.add_argument('--format', help = 'Select output format', default = "json", type = str, choices = ['json','csv'])

#SubParsers
sp = parser.add_subparsers(help='Subparser Init')

#Health Check
sp_healthcheck = sp.add_parser('healthcheck', help = 'Check Database Connection Status')

#Reset Functions
sp_resetvehicles = sp.add_parser('resetvehicles', help = 'Reset Vehicle Table from DB')
sp_resetpasses = sp.add_parser('resetpasses', help = 'Reset Pass Table from DB')
sp_resetpasses = sp.add_parser('fillpasses', help = 'Fill Pass Table with sample data')
sp_resetstations = sp.add_parser('resetstations', help = 'Reset Station Table from DB')
sp_operators = sp.add_parser('resetoperators', help = 'Reset Operator Table from DB')


#Passes Per Station
sp_passesperstation = sp.add_parser('passesperstation', parents = [formatparse], help = 'See all passes made through a station for a specific time period')
sp_passesperstation.add_argument('--station')
sp_passesperstation.add_argument('--datefrom')
sp_passesperstation.add_argument('--dateto')

#Charges By
sp_chargesby = sp.add_parser('chargesby', parents = [formatparse], help = 'See all charges made through a operator by another operator for a specific time period')
sp_chargesby.add_argument('--op1')
sp_chargesby.add_argument('--datefrom')
sp_chargesby.add_argument('--dateto')

#Passes Analysis
sp_passesanalysis = sp.add_parser('passesanalysis', parents = [formatparse], help = 'See all passes made through a station of op1 with a tag from op2 for a specific time period')
sp_passesanalysis.add_argument('--op1')
sp_passesanalysis.add_argument('--op2')
sp_passesanalysis.add_argument('--datefrom')
sp_passesanalysis.add_argument('--dateto')

#Passes Cost
sp_passescost = sp.add_parser('passescost', parents = [formatparse], help = 'See the number of passes (and their cost) made through a station of op1 with a tag from op2 for a specific time period')
sp_passescost.add_argument('--op1')
sp_passescost.add_argument('--op2')
sp_passescost.add_argument('--datefrom')
sp_passescost.add_argument('--dateto')

#Passes Alloc
sp_passesalloc = sp.add_parser('passesalloc', parents = [formatparse], help = 'See number of passes made through each operator')
sp_passesalloc.add_argument('--datefrom')
sp_passesalloc.add_argument('--dateto')

#Admin
sp_admin = sp.add_parser('admin', help = 'Perform admin operations')
sp_admin.add_argument('--passesupd', action='store_true', help='Flag to insert Passes from a CSV file')
sp_admin.add_argument('--source')

args = parser.parse_args()

host_name = os.environ.get('API_HOST_NAME', 'localhost')

if sys.argv[1] == 'healthcheck':
    request_string = f"http://{host_name}:9103/interoperability/api/admin/healthcheck"
    request = requests.get(request_string)
elif sys.argv[1] == 'resetvehicles':
    request_string = f"http://{host_name}:9103/interoperability/api/admin/resetvehicles"
    request = requests.post(request_string)
elif sys.argv[1] == 'resetstations':
    request_string = f"http://{host_name}:9103/interoperability/api/admin/resetstations"
    request = requests.post(request_string)
elif sys.argv[1] == 'resetpasses':
    request_string = f"http://{host_name}:9103/interoperability/api/admin/resetpasses"
    request = requests.post(request_string)
elif sys.argv[1] == 'fillpasses':
    request_string = f"http://{host_name}:9103/interoperability/api/admin/fillpasses"
    request = requests.post(request_string)
elif sys.argv[1] == 'resetoperators':
    request_string = f"http://{host_name}:9103/interoperability/api/admin/resetoperators"
    request = requests.post(request_string)
elif sys.argv[1] == 'admin':
    if (args.passesupd):
        result = insert.insertPass(args.source)
        if (result == "SUCCESS"):
            print('{"status":"OK"}')
        else:
            print('{"status":"failed","error":"'+ result+'"}')
        sys.exit()
elif sys.argv[1] == 'passesperstation':
    request_string = f"http://{host_name}:9103/interoperability/api/PassesPerStation/"+args.station+"/"+args.datefrom+"/"+args.dateto+"?format="+args.format
    request = requests.get(request_string)
elif sys.argv[1] == 'chargesby':
    request_string = f"http://{host_name}:9103/interoperability/api/ChargesBy/"+args.op1+"/"+args.datefrom+"/"+args.dateto+"?format="+args.format
    request = requests.get(request_string)
elif sys.argv[1] == 'passesanalysis':
    request_string = f"http://{host_name}:9103/interoperability/api/PassesAnalysis/"+args.op1+"/"+args.op2+"/"+args.datefrom+"/"+args.dateto+"?format="+args.format
    request = requests.get(request_string)
elif sys.argv[1] == 'passescost':
    request_string = f"http://{host_name}:9103/interoperability/api/PassesCost/"+args.op1+"/"+args.op2+"/"+args.datefrom+"/"+args.dateto+"?format="+args.format
    request = requests.get(request_string)
elif sys.argv[1] == 'passesalloc':
    request_string = f"http://{host_name}:9103/interoperability/api/PassesAlloc/"+args.datefrom+"/"+args.dateto+"?format="+args.format
    request = requests.get(request_string)


decoded = request.content.decode('utf-8')
print(decoded)
